'use client';

import ClearIcon from '@mui/icons-material/Clear';
import SearchIcon from '@mui/icons-material/Search';
import Grid from '@mui/material/Grid';
import IconButton from '@mui/material/IconButton';
import InputAdornment from '@mui/material/InputAdornment';
import TextField from '@mui/material/TextField';
import Typography from '@mui/material/Typography';
import debounce from 'lodash.debounce';
import React, { useEffect, useMemo, useState } from 'react';

import { trpc } from '@aei/app/src/client/trpcClient';
import { centeredAlertContainerGridProps, centeredContainerGridProps, ulComponentResetStyles } from '@aei/app/src/utils/grid';
import { linkRegistry } from '@aei/app/src/utils/routes/registry';
import { CaseCard } from '@aei/ui/src/CaseCard';
import { ErrorAlert } from '@aei/ui/src/ErrorAlert';
import { LoadingArea } from '@aei/ui/src/LoadingArea';

export interface CaseListPageProps {
  params: { authorityId: string };
}

export function CaseListPage({ params: { authorityId } }: CaseListPageProps) {
  const queryRef = React.createRef<HTMLInputElement>();
  const [searchQueryManipulated, setSearchQueryManipulated] = useState(false);
  const [searchQuery, setSearchQuery] = useState<string | null>(null);

  const { data, error, isInitialLoading, isLoading, refetch } = trpc.listCases.useQuery({
    orderBy: {},
    filterBy: {
      authorityIds: [authorityId],
      query: searchQuery,
    },
  });

  const handleSearchQueryChange = (event: React.ChangeEvent<HTMLInputElement>) => {
    setSearchQueryManipulated(true);
    setSearchQuery(event.target.value);
  };

  const debounedHandleClearQuery = useMemo(() => debounce(handleSearchQueryChange, 500), []);
  useEffect(() => {
    return () => {
      debounedHandleClearQuery.cancel();
    };
  }, [debounedHandleClearQuery]);

  const casesWrappers = data?.casesWrappers || [];
  const openCasesWrappers = casesWrappers.filter((caseWrapper) => {
    return !caseWrapper.case.closedAt;
  });
  const closeCasesWrappers = casesWrappers.filter((caseWrapper) => {
    return !!caseWrapper.case.closedAt;
  });

  if (error) {
    return (
      <Grid container {...centeredAlertContainerGridProps}>
        <ErrorAlert errors={[error]} refetchs={[refetch]} />
      </Grid>
    );
  } else if (isInitialLoading && !searchQueryManipulated) {
    return <LoadingArea ariaLabelTarget="page" />;
  }

  const handleClearQuery = () => {
    setSearchQuery(null);

    // We did not bind the TextField to "searchQuery" to allow delaying requests
    if (queryRef.current) {
      queryRef.current.value = '';
    }
  };

  return (
    <>
      <Grid container {...centeredContainerGridProps} alignContent="flex-start">
        <Grid item xs={12} sx={{ pb: 3 }}>
          <Typography component="h1" variant="h5">
            Tous les dossiers de la collectivité
          </Typography>
        </Grid>
        <Grid item xs={12} sx={{ mb: 3 }}>
          <TextField
            type="text"
            name="search"
            label="Rechercher..."
            inputRef={queryRef}
            onChange={debounedHandleClearQuery}
            fullWidth
            InputProps={{
              startAdornment: (
                <InputAdornment position="start">
                  <SearchIcon />
                </InputAdornment>
              ),
              endAdornment: (
                <InputAdornment position="end">
                  {searchQuery && searchQuery !== '' && (
                    <IconButton aria-label="effacer la recherche" onClick={handleClearQuery}>
                      <ClearIcon />
                    </IconButton>
                  )}
                </InputAdornment>
              ),
            }}
          />
        </Grid>
        {!isLoading ? (
          <>
            <Grid item xs={12} sx={{ py: 3 }}>
              <Typography component="h2" variant="h6">
                Dossiers ouverts
              </Typography>
            </Grid>
            {openCasesWrappers.length ? (
              <Grid container component="ul" spacing={3} sx={ulComponentResetStyles}>
                {openCasesWrappers.map((caseWrapper) => (
                  <Grid key={caseWrapper.case.id} item component="li" xs={12} sm={6}>
                    <CaseCard
                      caseLink={linkRegistry.get('case', {
                        authorityId: caseWrapper.case.authorityId,
                        caseId: caseWrapper.case.id,
                      })}
                      case={caseWrapper.case}
                      citizen={caseWrapper.citizen}
                      agent={caseWrapper.agent || undefined}
                      unprocessedMessages={caseWrapper.unprocessedMessages || 0}
                    />
                  </Grid>
                ))}
              </Grid>
            ) : (
              <Grid item xs={12}>
                <Typography variant="body2">Aucun dossier est en cours de traitement</Typography>
              </Grid>
            )}
            <Grid item xs={12} sx={{ py: 3 }}>
              <Typography component="h2" variant="h6">
                Dossiers clôturés
              </Typography>
            </Grid>
            {closeCasesWrappers.length ? (
              <Grid container component="ul" spacing={3} sx={ulComponentResetStyles}>
                {closeCasesWrappers.map((caseWrapper) => (
                  <Grid key={caseWrapper.case.id} item component="li" xs={12} sm={6}>
                    <CaseCard
                      caseLink={linkRegistry.get('case', {
                        authorityId: caseWrapper.case.authorityId,
                        caseId: caseWrapper.case.id,
                      })}
                      case={caseWrapper.case}
                      citizen={caseWrapper.citizen}
                      agent={caseWrapper.agent || undefined}
                      unprocessedMessages={caseWrapper.unprocessedMessages || 0}
                    />
                  </Grid>
                ))}
              </Grid>
            ) : (
              <Grid item xs={12}>
                <Typography variant="body2">Il n&apos;y a pour l&apos;instant aucun dossier clôturé</Typography>
              </Grid>
            )}
          </>
        ) : (
          <LoadingArea ariaLabelTarget="liste des dossiers" />
        )}
      </Grid>
    </>
  );
}
