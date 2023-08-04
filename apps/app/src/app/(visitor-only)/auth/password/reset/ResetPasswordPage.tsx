'use client';

import Grid from '@mui/material/Grid';
import Typography from '@mui/material/Typography';
import { useSearchParams } from 'next/navigation';
import { createContext, useContext } from 'react';

import { ResetPasswordForm } from '@aei/app/src/app/(visitor-only)/auth/password/reset/ResetPasswordForm';
import { ResetPasswordPrefillSchema } from '@aei/app/src/models/actions/auth';
import { formTitleProps } from '@aei/app/src/utils/form';
import { centeredAlertContainerGridProps, centeredFormContainerGridProps } from '@aei/app/src/utils/grid';
import { ErrorAlert } from '@aei/ui/src/ErrorAlert';

export const ResetPasswordPageContext = createContext({
  ContextualResetPasswordForm: ResetPasswordForm,
});

export function ResetPasswordPage() {
  const { ContextualResetPasswordForm } = useContext(ResetPasswordPageContext);

  const searchParams = useSearchParams();
  const resetToken = searchParams.get('token');

  if (!resetToken) {
    const error = new Error(`Le jeton de réinitialisation de mot de passe n'est pas détecté, merci de bien copier le lien depuis l'email.`);

    return (
      <Grid container {...centeredAlertContainerGridProps}>
        <ErrorAlert errors={[error]} />
      </Grid>
    );
  }

  return (
    <Grid container {...centeredFormContainerGridProps}>
      <Typography component="h1" {...formTitleProps}>
        Redéfinir votre mot de passe
      </Typography>
      <ContextualResetPasswordForm
        prefill={ResetPasswordPrefillSchema.parse({
          token: resetToken,
        })}
      />
    </Grid>
  );
}
