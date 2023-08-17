import PersonRemoveIcon from '@mui/icons-material/PersonRemove';
import IconButton from '@mui/material/IconButton';
import type { GridColDef } from '@mui/x-data-grid';
import { DataGrid } from '@mui/x-data-grid/DataGrid';

import { useApiInterventionsIdDelete } from '@aei/app/src/client/generated/components';
import { InterventionSchemaType } from '@aei/app/src/models/entities/intervention';
import { nameof } from '@aei/app/src/utils/typescript';
import { useSingletonConfirmationDialog } from '@aei/ui/src/modal/useModal';

const typedNameof = nameof<InterventionSchemaType>;

export interface InterventionListProps {
  interventions: InterventionSchemaType[];
}

export function InterventionList({ interventions }: InterventionListProps) {
  const deleteIntervention = useApiInterventionsIdDelete();
  const { showConfirmationDialog } = useSingletonConfirmationDialog();

  const deleteInterventionAction = async (intervention: InterventionSchemaType) => {
    showConfirmationDialog({
      description: <>Êtes-vous sûr de vouloir supprimer l'intervention {intervention.firstname} ?</>,
      onConfirm: async () => {
        await deleteIntervention.mutateAsync({
          pathParams: {
            id: intervention.userId,
          },
        });
      },
    });
  };

  // To type options functions have a look at https://github.com/mui/mui-x/pull/4064
  const columns: GridColDef<InterventionSchemaType>[] = [
    {
      field: typedNameof('email'),
      headerName: 'Email',
      flex: 1.5,
    },
    {
      field: typedNameof('firstname'),
      headerName: 'Prénom',
      flex: 1,
    },
    {
      field: typedNameof('lastname'),
      headerName: 'Nom',
      flex: 1,
    },
    {
      field: 'actions',
      headerName: 'Actions',
      flex: 0.5,
      headerAlign: 'right',
      align: 'right',
      sortable: false,
      renderCell: (params) => {
        return (
          <IconButton
            aria-label="enlever les droits"
            onClick={async () => {
              await deleteInterventionAction(params.row);
            }}
            size="small"
          >
            <PersonRemoveIcon />
          </IconButton>
        );
      },
    },
  ];

  return (
    <>
      <DataGrid
        rows={interventions}
        columns={columns}
        pageSize={10}
        rowsPerPageOptions={[10]}
        autoHeight
        experimentalFeatures={{ newEditingApi: false }}
        disableColumnFilter
        disableColumnMenu
        disableSelectionOnClick
        // loading={false}
        aria-label="liste des interventionistrateurs"
      />
    </>
  );
}
