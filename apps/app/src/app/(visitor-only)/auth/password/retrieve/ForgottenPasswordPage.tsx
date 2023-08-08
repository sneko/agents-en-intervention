'use client';

import Alert from '@mui/material/Alert';
import Grid from '@mui/material/Grid';
import Typography from '@mui/material/Typography';
import { createContext, useContext, useState } from 'react';

import { RetrievePasswordForm } from '@aei/app/src/app/(visitor-only)/auth/password/retrieve/RetrievePasswordForm';
import { formTitleProps } from '@aei/app/src/utils/form';
import { centeredFormContainerGridProps } from '@aei/app/src/utils/grid';

export const ForgottenPasswordPageContext = createContext({
  ContextualRetrievePasswordForm: RetrievePasswordForm,
});

export function ForgottenPasswordPage() {
  const { ContextualRetrievePasswordForm } = useContext(ForgottenPasswordPageContext);

  const [requestCommitted, setRequestCommitted] = useState<boolean>(false);

  return (
    <Grid container {...centeredFormContainerGridProps}>
      {requestCommitted ? (
        <>
          <Grid item xs={12}>
            <Alert severity="success">Nous venons de vous envoyer un email pour réinitialiser votre mot de passe.</Alert>
          </Grid>
        </>
      ) : (
        <>
          <Typography component="h1" {...formTitleProps}>
            Mot de passe oublié ?
          </Typography>
          <ContextualRetrievePasswordForm
            onSuccess={async () => {
              setRequestCommitted(true);
            }}
          />
        </>
      )}
    </Grid>
  );
}
