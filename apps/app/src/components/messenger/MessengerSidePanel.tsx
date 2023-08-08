import KeyboardArrowDownRoundedIcon from '@mui/icons-material/KeyboardArrowDownRounded';
import KeyboardArrowUpRoundedIcon from '@mui/icons-material/KeyboardArrowUpRounded';
import Box from '@mui/material/Box';
import Button from '@mui/material/Button';
import Collapse from '@mui/material/Collapse';
import IconButton from '@mui/material/IconButton';
import Typography from '@mui/material/Typography';
import { EventEmitter } from 'eventemitter3';
import { createContext, useContext, useMemo, useState } from 'react';

import { MessengerMessageList } from '@aei/app/src/components/messenger/MessengerMessageList';
import { MessengerSender } from '@aei/app/src/components/messenger/MessengerSender';
import { MessageSchemaType } from '@aei/app/src/models/entities/messenger';

export const MessengerSidePanelContext = createContext({
  ContextualMessengerSender: MessengerSender,
});

export interface MessengerSidePanelProps {
  caseId: string;
  messages: MessageSchemaType[];
  selectedMessage?: MessageSchemaType | null;
  onSelectedMessage?: (message: MessageSchemaType) => void;
}

export function MessengerSidePanel({ caseId, messages, selectedMessage, onSelectedMessage }: MessengerSidePanelProps) {
  const { ContextualMessengerSender } = useContext(MessengerSidePanelContext);

  const [senderModalEventEmitter, setSenderModalEventEmitter] = useState<EventEmitter>(() => new EventEmitter());
  const [messagesToProcessExpanded, setMessagesToProcessExpanded] = useState<boolean>(true);
  const [sortedMessagesExpanded, setSortedMessagesExpanded] = useState<boolean>(true);

  const { sortedMessages, messagesToProcess } = useMemo(() => {
    const sorted = messages.sort((a, b) => {
      return +b.createdAt - +a.createdAt;
    });

    return {
      sortedMessages: sorted,
      messagesToProcess: sorted.filter((message) => {
        // Note: if "null" it means "as processed" cannot be managed (for sent emails for example)
        return message.consideredAsProcessed === false;
      }),
    };
  }, [messages]);

  return (
    <>
      <ContextualMessengerSender caseId={caseId} eventEmitter={senderModalEventEmitter} />
      <Button
        onClick={(event) => {
          senderModalEventEmitter.emit('click', event);
        }}
        size="large"
        variant="contained"
        fullWidth
      >
        Nouveau message
      </Button>
      {messages.length > 0 && (
        <>
          <Box
            sx={{
              p: 2,
              mb: 1,
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'space-between',
            }}
          >
            <Typography
              fontWeight={700}
              sx={{
                fontSize: '10px',
                textTransform: 'uppercase',
                letterSpacing: '.1rem',
              }}
            >
              À traiter
            </Typography>
            <IconButton
              onClick={() => {
                setMessagesToProcessExpanded(!messagesToProcessExpanded);
              }}
              size="small"
              color="primary"
              aria-label="montrer la liste des messages à traiter"
              aria-expanded={messagesToProcessExpanded}
            >
              {messagesToProcessExpanded ? (
                <KeyboardArrowUpRoundedIcon fontSize="small" color="primary" />
              ) : (
                <KeyboardArrowDownRoundedIcon fontSize="small" color="primary" />
              )}
            </IconButton>
          </Box>
          <Collapse in={messagesToProcessExpanded} timeout="auto" unmountOnExit>
            {messagesToProcess.length > 0 ? (
              <MessengerMessageList
                messages={messagesToProcess}
                selectedMessage={selectedMessage}
                onMessageClick={(message) => {
                  onSelectedMessage && onSelectedMessage(message);
                }}
              />
            ) : (
              <Box sx={{ py: 10 }}>
                <Typography variant="body2" sx={{ textAlign: 'center' }}>
                  Vous avez traité tous les messages
                </Typography>
              </Box>
            )}
          </Collapse>
        </>
      )}
      <Box
        sx={{
          p: 2,
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'space-between',
        }}
      >
        <Typography
          fontWeight={700}
          sx={{
            fontSize: '10px',
            textTransform: 'uppercase',
            letterSpacing: '.1rem',
          }}
        >
          Tous les messages
        </Typography>
        <IconButton
          onClick={() => {
            setSortedMessagesExpanded(!sortedMessagesExpanded);
          }}
          size="small"
          color="primary"
          aria-label="montrer la liste des messages"
          aria-expanded={sortedMessagesExpanded}
        >
          {sortedMessagesExpanded ? (
            <KeyboardArrowUpRoundedIcon fontSize="small" color="primary" />
          ) : (
            <KeyboardArrowDownRoundedIcon fontSize="small" color="primary" />
          )}
        </IconButton>
      </Box>
      <Collapse in={sortedMessagesExpanded} timeout="auto" unmountOnExit>
        {sortedMessages.length > 0 ? (
          <MessengerMessageList
            messages={sortedMessages}
            selectedMessage={selectedMessage}
            onMessageClick={(message) => {
              onSelectedMessage && onSelectedMessage(message);
            }}
          />
        ) : (
          <Box sx={{ py: 10 }}>
            <Typography variant="body2" sx={{ textAlign: 'center' }}>
              Il n&apos;y a aucun message
            </Typography>
          </Box>
        )}
      </Collapse>
    </>
  );
}
