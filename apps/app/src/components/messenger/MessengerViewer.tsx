import CheckBoxIcon from '@mui/icons-material/CheckBox';
import CheckBoxOutlineBlankIcon from '@mui/icons-material/CheckBoxOutlineBlank';
import DeleteRoundedIcon from '@mui/icons-material/DeleteRounded';
import ForwardToInboxRoundedIcon from '@mui/icons-material/ForwardToInboxRounded';
import Box from '@mui/material/Box';
import Button from '@mui/material/Button';
import Checkbox from '@mui/material/Checkbox';
import Chip from '@mui/material/Chip';
import Divider from '@mui/material/Divider';
import IconButton from '@mui/material/IconButton';
import Paper from '@mui/material/Paper';
import Typography from '@mui/material/Typography';
import { EventEmitter } from 'eventemitter3';
import { createContext, useContext, useState } from 'react';
import { useTranslation } from 'react-i18next';

import { trpc } from '@aei/app/src/client/trpcClient';
import { FileList } from '@aei/app/src/components/FileList';
import { MessengerSender } from '@aei/app/src/components/messenger/MessengerSender';
import { MessageSchemaType } from '@aei/app/src/models/entities/messenger';
import { Avatar } from '@aei/ui/src/Avatar';
import { LexicalRenderer } from '@aei/ui/src/LexicalRenderer';
import { useSingletonErrorDialog } from '@aei/ui/src/modal/useModal';

export const MessengerViewerContext = createContext({
  ContextualMessengerSender: MessengerSender,
});

export interface MessengerViewerProps {
  caseId: string;
  message: MessageSchemaType;
}

export function MessengerViewer({ caseId, message }: MessengerViewerProps) {
  const { ContextualMessengerSender } = useContext(MessengerViewerContext);
  const { t } = useTranslation('common');
  const { showErrorDialog } = useSingletonErrorDialog();

  const updateMessageMetadata = trpc.updateMessageMetadata.useMutation();

  const [senderModalEventEmitter, setSenderModalEventEmitter] = useState<EventEmitter>(() => new EventEmitter());

  return (
    <Paper
      variant="outlined"
      sx={{
        minHeight: 500,
        borderRadius: 'sm',
        p: 2,
        mb: 3,
        bgcolor: 'background.componentBg',
      }}
    >
      <Box
        sx={{
          display: 'flex',
          justifyContent: 'space-between',
          alignItems: 'center',
          flexWrap: 'wrap',
          gap: 2,
        }}
      >
        <Box sx={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
          <Avatar fullName={message.from.name || message.from.email} size={40} />
          <Box
            sx={{
              ml: 2,
            }}
          >
            <Typography
              variant="subtitle2"
              sx={{
                textColor: 'text.primary',
                mb: 0.5,
              }}
            >
              {message.from.name || message.from.email}
            </Typography>
            <Typography
              variant="body2"
              sx={{
                textColor: 'text.tertiary',
              }}
            >
              {t('date.longWithTime', { date: message.createdAt })}
            </Typography>
          </Box>
        </Box>
        <Box sx={{ display: 'flex', height: '32px', flexDirection: 'row', gap: 2 }}>
          {message.consideredAsProcessed !== null && (
            <Button
              onClick={async () => {
                try {
                  await updateMessageMetadata.mutateAsync({
                    messageId: message.id,
                    markAsProcessed: !message.consideredAsProcessed,
                  });
                } catch (err) {
                  showErrorDialog({
                    description: <>Une erreur est survenue au moment de changer le statut du message.</>,
                    error: err as unknown as Error,
                  });

                  throw err;
                }
              }}
              variant="outlined"
              color="primary"
              size="small"
              startIcon={message.consideredAsProcessed ? <CheckBoxIcon /> : <CheckBoxOutlineBlankIcon />}
            >
              Traité
            </Button>
          )}
          <ContextualMessengerSender
            caseId={caseId}
            eventEmitter={senderModalEventEmitter}
            prefillRecipients={[message.from]}
            prefillSubject={message.subject}
          />
          <Button
            onClick={(event) => {
              senderModalEventEmitter.emit('click', event);
            }}
            variant="outlined"
            color="primary"
            size="small"
          >
            Répondre
          </Button>
        </Box>
      </Box>
      <Divider variant="fullWidth" sx={{ p: 0, mt: 2 }} />
      <Box sx={{ py: 2, display: 'flex', flexDirection: 'column', alignItems: 'start' }}>
        <Typography
          variant="h5"
          sx={{
            textColor: 'text.primary',
          }}
        >
          {message.subject}
        </Typography>
        <Box
          sx={{
            mt: 1,
            display: 'flex',
            alignItems: 'center',
            gap: 1,
            flexWrap: 'wrap',
          }}
        >
          <Box>
            <Typography
              component="span"
              variant="body2"
              sx={{
                textColor: 'text.secondary',
                mr: 1,
                display: 'inline-block',
              }}
            >
              De
            </Typography>
            <Chip label={message.from.email} size="small" variant="outlined" onClick={() => {}} />
          </Box>
          <Box>
            <Typography
              component="span"
              variant="body2"
              sx={{
                textColor: 'text.secondary',
                mr: 1,
                display: 'inline-block',
              }}
            >
              à
            </Typography>
            {message.to.map((recipient) => {
              return <Chip key={recipient.id} label={recipient.email} size="small" variant="outlined" onClick={() => {}} sx={{ mr: 1 }} />;
            })}
          </Box>
        </Box>
      </Box>
      <Divider variant="fullWidth" sx={{ p: 0 }} />
      <Box sx={{ py: 2 }}>
        <LexicalRenderer inlineEditorState={message.content} />
      </Box>
      {message.attachments && message.attachments.length > 0 && (
        <>
          <Divider variant="fullWidth" sx={{ p: 0 }} />
          <Typography
            variant="body2"
            sx={{
              fontWeight: 'md',
              textColor: 'text.primary',
              mt: 2,
              mb: 2,
            }}
          >
            {t('file.attachment', { count: message.attachments.length })}
          </Typography>
          <FileList files={message.attachments} onRemove={async (file) => {}} readonly />
        </>
      )}
    </Paper>
  );
}
