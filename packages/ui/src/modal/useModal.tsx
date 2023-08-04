import { useContext } from 'react';

import { ConfirmationDialog, ConfirmationDialogProps } from '@aei/ui/src/ConfirmationDialog';
import { ErrorDialog, ErrorDialogProps } from '@aei/ui/src/ErrorDialog';
import { ModalContext } from '@aei/ui/src/modal/ModalContext';

export const useSingletonModal = () => {
  return useContext(ModalContext);
};

export type ShowConfirmationDialogProps = Pick<ConfirmationDialogProps, 'title' | 'description' | 'onConfirm' | 'onCancel'>;

export const useSingletonConfirmationDialog = () => {
  const { showModal } = useSingletonModal();

  return {
    showConfirmationDialog(confirmationDialogProps: ShowConfirmationDialogProps) {
      showModal((modalProps) => {
        return <ConfirmationDialog {...modalProps} {...confirmationDialogProps} />;
      });
    },
  };
};

export type ShowErrorDialogProps = Pick<ErrorDialogProps, 'title' | 'description' | 'error'>;

export const useSingletonErrorDialog = () => {
  const { showModal } = useSingletonModal();

  return {
    showErrorDialog(errorDialogProps: ShowErrorDialogProps) {
      showModal((modalProps) => {
        return <ErrorDialog {...modalProps} {...errorDialogProps} />;
      });
    },
  };
};
