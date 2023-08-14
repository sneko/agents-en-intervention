'use client';

import { Footer } from '@codegouvfr/react-dsfr/Footer';
import { Header } from '@codegouvfr/react-dsfr/Header';
import Grid from '@mui/material/Grid';
import { usePathname, useRouter } from 'next/navigation';
import { PropsWithChildren, useEffect, useState } from 'react';

import { useApiUsersUserIdinterventionsGetCollection } from '@aei/app/src/client/generated/components';
import { signIn, useSession } from '@aei/app/src/proxies/next-auth/react';
import { commonFooterAttributes, commonHeaderAttributes } from '@aei/app/src/utils/dsfr';
import { centeredAlertContainerGridProps } from '@aei/app/src/utils/grid';
import { ErrorAlert } from '@aei/ui/src/ErrorAlert';
import { LoadingArea } from '@aei/ui/src/LoadingArea';
import { ContentWrapper } from '@aei/ui/src/layouts/ContentWrapper';

export function PrivateLayout(props: PropsWithChildren) {
  const router = useRouter();
  const pathname = usePathname();
  const sessionWrapper = useSession();
  const [logoutCommitted, setLogoutCommitted] = useState(false);

  const { data, error, isLoading, refetch } = useApiUsersUserIdinterventionsGetCollection({
    pathParams: {
      userId: 'TODO',
    },
  });

  const testTodo = data;

  useEffect(() => {
    if (sessionWrapper.status === 'unauthenticated' && !logoutCommitted) {
      signIn();
    }
  }, [logoutCommitted, router, sessionWrapper.status]);

  if (isLoading || sessionWrapper.status !== 'authenticated') {
    return <LoadingArea ariaLabelTarget="contenu" />;
  } else if (error || !testTodo) {
    return (
      <Grid container {...centeredAlertContainerGridProps}>
        <ErrorAlert errors={[error]} refetchs={[refetch]} />
      </Grid>
    );
  }

  return (
    <>
      <Header {...commonHeaderAttributes} />
      <ContentWrapper>{props.children}</ContentWrapper>
      <Footer {...commonFooterAttributes} />
    </>
  );
}
