'use client';

import Alert from '@mui/material/Alert';
import Grid from '@mui/material/Grid';
import Typography from '@mui/material/Typography';
import Image from 'next/image';
import { createContext, useContext, useState } from 'react';

import { RequestCaseForm } from '@aei/app/src/app/(public)/request/[authority]/RequestCaseForm';
import { trpc } from '@aei/app/src/client/trpcClient';
import { notFound } from '@aei/app/src/proxies/next/navigation';
import { centeredAlertContainerGridProps, mdCenteredFormContainerGridProps } from '@aei/app/src/utils/grid';
import { ErrorAlert } from '@aei/ui/src/ErrorAlert';
import { LoadingArea } from '@aei/ui/src/LoadingArea';

export const RequestCasePageContext = createContext({
  ContextualRequestCaseForm: RequestCaseForm,
});

export interface RequestCasePageProps {
  params: { authority: string };
}

export function RequestCasePage({ params: { authority: authoritySlug } }: RequestCasePageProps) {
  const { ContextualRequestCaseForm } = useContext(RequestCasePageContext);

  const [requestCommitted, setRequestCommitted] = useState<boolean>(false);

  const { data, error, isLoading, refetch } = trpc.getPublicFacingAuthority.useQuery({
    slug: authoritySlug,
  });

  const authority = data?.authority;

  if (error) {
    return (
      <Grid container {...centeredAlertContainerGridProps}>
        <ErrorAlert errors={[error]} refetchs={[refetch]} />
      </Grid>
    );
  } else if (isLoading) {
    return <LoadingArea ariaLabelTarget="page" />;
  } else if (!authority) {
    return notFound();
  }

  return (
    <Grid container {...mdCenteredFormContainerGridProps}>
      {requestCommitted ? (
        <>
          <Grid item xs={12}>
            <Alert severity="success">Votre demande a bien été prise en compte, un médiateur va prendre contact avec vous sous quelques jours.</Alert>
          </Grid>
        </>
      ) : (
        <>
          {!!authority.logo ? (
            <Image
              src={authority.logo.url}
              width={300}
              height={100}
              alt="logo de la collectivité"
              style={{
                objectFit: 'contain',
                margin: '0 auto 20px',
              }}
            />
          ) : (
            <Typography component="h2" variant="h6" align="center">
              {authority.name}
            </Typography>
          )}
          <Typography component="h1" variant="h5" align="center" gutterBottom sx={{ mb: 5 }}>
            Lancer ma démarche de médiation
          </Typography>
          <ContextualRequestCaseForm
            prefill={{
              authorityId: authority.id,
            }}
            onSuccess={async () => {
              setRequestCommitted(true);
            }}
          />
        </>
      )}
    </Grid>
  );
}
