'use client';

import Grid from '@mui/material/Grid';
import Typography from '@mui/material/Typography';
import { createContext, useContext } from 'react';

import { EditAuthorityForm } from '@aei/app/src/app/(private)/dashboard/authority/[authorityId]/edit/EditAuthorityForm';
import { trpc } from '@aei/app/src/client/trpcClient';
import { UpdateAuthorityPrefillSchema } from '@aei/app/src/models/actions/authority';
import { formTitleProps } from '@aei/app/src/utils/form';
import { centeredAlertContainerGridProps, centeredFormContainerGridProps } from '@aei/app/src/utils/grid';
import { ErrorAlert } from '@aei/ui/src/ErrorAlert';
import { LoadingArea } from '@aei/ui/src/LoadingArea';

export const AuthorityEditPageContext = createContext({
  ContextualEditAuthorityForm: EditAuthorityForm,
});

export interface AuthorityEditPageProps {
  params: { authorityId: string };
}

export function AuthorityEditPage({ params: { authorityId } }: AuthorityEditPageProps) {
  const { ContextualEditAuthorityForm } = useContext(AuthorityEditPageContext);

  const { data, error, isLoading, refetch } = trpc.getAuthority.useQuery({
    id: authorityId,
  });

  if (isLoading) {
    return <LoadingArea ariaLabelTarget="contenu" />;
  } else if (error) {
    return (
      <Grid container {...centeredAlertContainerGridProps}>
        <ErrorAlert errors={[error]} refetchs={[refetch]} />
      </Grid>
    );
  }

  const authority = data.authority;

  return (
    <Grid container {...centeredFormContainerGridProps}>
      <Typography component="h1" {...formTitleProps}>
        Éditer la collectivité
      </Typography>
      <ContextualEditAuthorityForm
        slug={authority.slug}
        logo={authority.logo}
        prefill={UpdateAuthorityPrefillSchema.parse({
          authorityId: authority.id,
          name: authority.name,
          type: authority.type,
          logoAttachmentId: authority.logo?.id || null,
        })}
      />
    </Grid>
  );
}
