'use client';

import CloseIcon from '@mui/icons-material/Close';
import DeleteForeverIcon from '@mui/icons-material/DeleteForever';
import MoreVertIcon from '@mui/icons-material/MoreVert';
import Card from '@mui/material/Card';
import CardActionArea from '@mui/material/CardActionArea';
import CardContent from '@mui/material/CardContent';
import Dialog from '@mui/material/Dialog';
import DialogContent from '@mui/material/DialogContent';
import DialogTitle from '@mui/material/DialogTitle';
import Grid from '@mui/material/Grid';
import IconButton from '@mui/material/IconButton';
import ListItemIcon from '@mui/material/ListItemIcon';
import Menu from '@mui/material/Menu';
import MenuItem from '@mui/material/MenuItem';
import Tooltip from '@mui/material/Tooltip';
import Typography from '@mui/material/Typography';
import { createContext, useContext, useState } from 'react';
import { useTranslation } from 'react-i18next';

import { trpc } from '@aei/app/src/client/trpcClient';
import { CaseNoteSchemaType } from '@aei/app/src/models/entities/case';
import { useSingletonConfirmationDialog } from '@aei/ui/src/modal/useModal';
import { inlineEditorStateToText } from '@aei/ui/src/utils/lexical';
import { menuPaperProps } from '@aei/ui/src/utils/menu';

import { UpdateNoteForm } from '../app/(private)/dashboard/authority/[authorityId]/case/[caseId]/UpdateNoteForm';

export const NodeCardContext = createContext({
  ContextualUpdateNoteForm: UpdateNoteForm,
});

export interface NoteCardProps {
  note: CaseNoteSchemaType;
}

export function NoteCard(props: NoteCardProps) {
  const { t } = useTranslation('common');
  const { ContextualUpdateNoteForm } = useContext(NodeCardContext);

  const removeNoteFromCase = trpc.removeNoteFromCase.useMutation();

  const [modalOpen, setModalOpen] = useState<boolean>(false);
  const handeOpenModal = () => {
    setModalOpen(true);
  };
  const handleCloseModal = () => {
    setModalOpen(false);
  };

  const [menuAnchorEl, setAnchorEl] = useState<null | HTMLElement>(null);
  const menuOpen = Boolean(menuAnchorEl);
  const handleOpenMenu = (event: React.MouseEvent<HTMLElement>) => {
    setAnchorEl(event.currentTarget);
  };
  const handleCloseMenu = () => {
    setAnchorEl(null);
  };

  const { showConfirmationDialog } = useSingletonConfirmationDialog();

  const removeAction = async () => {
    showConfirmationDialog({
      description: <>Êtes-vous sûr de vouloir supprimer de cette note ?</>,
      onConfirm: async () => {
        const result = await removeNoteFromCase.mutateAsync({
          noteId: props.note.id,
        });

        handleCloseMenu();
        handleCloseModal();
      },
    });
  };

  return (
    <>
      <Card variant="outlined">
        <CardActionArea onClick={handeOpenModal}>
          <CardContent>
            <Grid container spacing={2} sx={{ justifyContent: 'space-between' }}>
              <Grid item xs="auto">
                <Typography
                  variant="body2"
                  sx={{
                    textColor: 'text.tertiary',
                  }}
                >
                  {t('date.short', { date: props.note.date })}
                </Typography>
              </Grid>
              <Grid item xs={12}>
                <Typography
                  variant="body2"
                  sx={{
                    textColor: 'text.tertiary',
                    overflow: 'hidden',
                    textOverflow: 'ellipsis',
                    whiteSpace: 'nowrap',
                  }}
                >
                  {inlineEditorStateToText(props.note.content) || 'Note'}
                </Typography>
              </Grid>
            </Grid>
          </CardContent>
        </CardActionArea>
      </Card>
      <Menu
        anchorEl={menuAnchorEl}
        id="note-menu"
        open={menuOpen}
        onClose={handleCloseMenu}
        onClick={handleCloseMenu}
        PaperProps={{ ...menuPaperProps }}
        transformOrigin={{ horizontal: 'right', vertical: 'top' }}
        anchorOrigin={{ horizontal: 'right', vertical: 'bottom' }}
      >
        <MenuItem onClick={removeAction}>
          <ListItemIcon>
            <DeleteForeverIcon fontSize="small" />
          </ListItemIcon>
          Supprimer
        </MenuItem>
      </Menu>
      <Dialog open={modalOpen} onClose={handleCloseModal} fullWidth maxWidth="lg">
        <DialogTitle>
          <Grid container spacing={2} justifyContent="space-between" alignItems="center">
            <Grid item xs="auto">
              Édition de note
            </Grid>
            <Grid item xs="auto">
              <Tooltip title="Options de la note">
                <IconButton
                  onClick={handleOpenMenu}
                  size="small"
                  sx={{ ml: 2 }}
                  aria-label="options"
                  aria-controls={modalOpen ? 'note-menu' : undefined}
                  aria-haspopup="true"
                  aria-expanded={modalOpen ? 'true' : undefined}
                >
                  <MoreVertIcon />
                </IconButton>
              </Tooltip>
              <IconButton onClick={handleCloseModal} size="small" sx={{ ml: 1 }}>
                <CloseIcon />
              </IconButton>
            </Grid>
          </Grid>
        </DialogTitle>
        <DialogContent>
          <ContextualUpdateNoteForm note={props.note} />
        </DialogContent>
      </Dialog>
    </>
  );
}
