'use client';

import Grid from '@mui/material/Grid';
import Typography from '@mui/material/Typography';
import { createContext, useContext } from 'react';

import { InviteAgentForm } from '@aei/app/src/app/(private)/dashboard/authority/[authorityId]/agent/add/InviteAgentForm';
import { trpc } from '@aei/app/src/client/trpcClient';
import { notFound } from '@aei/app/src/proxies/next/navigation';
import { formTitleProps } from '@aei/app/src/utils/form';
import { centeredAlertContainerGridProps, mdCenteredFormContainerGridProps } from '@aei/app/src/utils/grid';
import { ErrorAlert } from '@aei/ui/src/ErrorAlert';
import { LoadingArea } from '@aei/ui/src/LoadingArea';

export const AddAgentPageContext = createContext({
  ContextualInviteAgentForm: InviteAgentForm,
});

export interface AddAgentPageProps {
  params: { authorityId: string };
}

export function AddAgentPage({ params: { authorityId } }: AddAgentPageProps) {
  const { ContextualInviteAgentForm } = useContext(AddAgentPageContext);

  const { data, error, isLoading, refetch } = trpc.getAuthority.useQuery({
    id: authorityId,
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
      <Typography component="h1" {...formTitleProps}>
        Ajouter un médiateur à &quot;{authority.name}&quot;
      </Typography>
      <ContextualInviteAgentForm
        prefill={{
          authorityId: authority.id,
        }}
      />
    </Grid>
  );
}
