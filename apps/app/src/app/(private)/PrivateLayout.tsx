'use client';

import Box from '@mui/material/Box';
import Grid from '@mui/material/Grid';
import { usePathname, useRouter } from 'next/navigation';
import { PropsWithChildren, useCallback, useEffect, useState } from 'react';

import { useApiUsersUserIdinterventionsGetCollection } from '@aei/app/src/client/generated/components';
import { SideNav } from '@aei/app/src/components/SideNav';
import { TopNav, sideNavWidth } from '@aei/app/src/components/TopNav';
import { signIn, useSession } from '@aei/app/src/proxies/next-auth/react';
import { centeredAlertContainerGridProps } from '@aei/app/src/utils/grid';
import { ErrorAlert } from '@aei/ui/src/ErrorAlert';
import { LoadingArea } from '@aei/ui/src/LoadingArea';
import { ContentWrapper } from '@aei/ui/src/layouts/ContentWrapper';

export function PrivateLayout(props: PropsWithChildren) {
  const router = useRouter();
  const pathname = usePathname();
  const sessionWrapper = useSession();
  const [logoutCommitted, setLogoutCommitted] = useState(false);
  const [openNav, setOpenNav] = useState(false);

  const { data, error, isLoading, refetch } = useApiUsersUserIdinterventionsGetCollection({
    pathParams: {
      userId: 'TODO',
    },
  });

  const testTodo = data;

  useEffect(() => {
    if (openNav) {
      setOpenNav(false);
    }
  }, [pathname]);

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
      <TopNav onNavOpen={() => setOpenNav(true)} />
      <SideNav onClose={() => setOpenNav(false)} open={openNav} />
      <Box
        sx={(theme) => ({
          display: 'flex',
          flex: '1 1 auto',
          maxWidth: '100%',
          [theme.breakpoints.up('lg')]: {
            paddingLeft: sideNavWidth,
          },
        })}
      >
        <Box
          sx={{
            display: 'flex',
            flex: '1 1 auto',
            flexDirection: 'column',
            width: '100%',
          }}
        >
          <ContentWrapper>{props.children}</ContentWrapper>
        </Box>
      </Box>
    </>
  );
}
