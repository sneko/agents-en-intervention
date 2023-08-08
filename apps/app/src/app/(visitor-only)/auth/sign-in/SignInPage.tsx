'use client';

import Grid from '@mui/material/Grid';
import Typography from '@mui/material/Typography';
import { createContext, useContext } from 'react';

import { SignInForm } from '@aei/app/src/app/(visitor-only)/auth/sign-in/SignInForm';
import { formTitleProps } from '@aei/app/src/utils/form';
import { centeredFormContainerGridProps } from '@aei/app/src/utils/grid';

export const SignInPageContext = createContext({
  ContextualSignInForm: SignInForm,
});

export function SignInPage() {
  const { ContextualSignInForm } = useContext(SignInPageContext);

  return (
    <Grid container>
      <Grid item xs={12} lg={6} sx={{ display: 'flex', justifyContent: 'center' }}>
        <Grid container {...centeredFormContainerGridProps}>
          <Typography component="h1" {...formTitleProps}>
            Connexion
          </Typography>
          <ContextualSignInForm />
        </Grid>
      </Grid>
      <Grid
        item
        xs={12}
        lg={6}
        container
        direction={'column'}
        sx={{
          background: 'radial-gradient(50% 50% at 50% 50%, #122647 0%, #090E23 100%)',
          color: 'white',
          px: 3,
          py: 2,
          alignItems: 'center',
          justifyContent: 'center',
        }}
      >
        <Typography variant="body1" align="center">
          Cet espace est réservé aux médiateurs des collectivités ... TODO
        </Typography>
      </Grid>
    </Grid>
  );
}
