'use client';

import AddCircleOutlineIcon from '@mui/icons-material/AddCircleOutline';
import Button from '@mui/material/Button';
import Grid from '@mui/material/Grid';
import NextLink from 'next/link';

import { useApiEmployersEmployerIdinterventionsGetCollection } from '@aei/app/src/client/generated/components';
import { InterventionList } from '@aei/app/src/components/InterventionList';
import { centeredAlertContainerGridProps, centeredContainerGridProps } from '@aei/app/src/utils/grid';
import { AggregatedQueries } from '@aei/app/src/utils/react-query';
import { linkRegistry } from '@aei/app/src/utils/routes/registry';
import { ErrorAlert } from '@aei/ui/src/ErrorAlert';
import { LoadingArea } from '@aei/ui/src/LoadingArea';

export interface InterventionListPageProps {
  params: {};
}

export function InterventionListPage(props: InterventionListPageProps) {
  const listInterventions = useApiEmployersEmployerIdinterventionsGetCollection({
    pathParams: {
      employerId: 'TODO',
    },
  });

  // Aggregate those to get agents&co for filters
  const aggregatedQueries = new AggregatedQueries(listInterventions);

  const admins = listInterventions.data || [];

  if (aggregatedQueries.hasError) {
    return (
      <Grid container {...centeredAlertContainerGridProps}>
        <ErrorAlert errors={aggregatedQueries.errors} refetchs={aggregatedQueries.refetchs} />
      </Grid>
    );
  } else if (aggregatedQueries.isLoading) {
    return <LoadingArea ariaLabelTarget="page" />;
  }

  return (
    <>
      <Grid container {...centeredContainerGridProps}>
        <Grid container spacing={1} sx={{ pb: 3 }}>
          {/* <Grid item xs={12} md={7} sx={{ display: 'flex', alignItems: 'center' }}>
            Filters
          </Grid> */}
          <Grid item xs={12} md={5} sx={{ display: 'flex', alignItems: 'center', justifyContent: 'right' }}>
            <Button
              component={NextLink}
              // TODO
              // href={linkRegistry.get('addIntervention', undefined)}
              size="large"
              variant="contained"
              startIcon={<AddCircleOutlineIcon />}
            >
              Créer une intervention
            </Button>
          </Grid>
        </Grid>
        <Grid item xs={12}>
          <InterventionList admins={admins} />
        </Grid>
      </Grid>
    </>
  );
}
