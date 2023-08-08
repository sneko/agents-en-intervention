'use client';

import { zodResolver } from '@hookform/resolvers/zod';
import Button from '@mui/lab/LoadingButton';
import Grid from '@mui/material/Grid';
import TextField from '@mui/material/TextField';
import { DateTimePicker } from '@mui/x-date-pickers/DateTimePicker';
import { useForm } from 'react-hook-form';

import { trpc } from '@aei/app/src/client/trpcClient';
import { BaseForm } from '@aei/app/src/components/BaseForm';
import { UpdateCaseNoteSchema, UpdateCaseNoteSchemaType } from '@aei/app/src/models/actions/case';
import { CaseNoteSchemaType } from '@aei/app/src/models/entities/case';
import { EditorWrapper } from '@aei/ui/src/Editor/EditorWrapper';

export interface UpdateNoteFormProps {
  note: CaseNoteSchemaType;
}

export function UpdateNoteForm(props: UpdateNoteFormProps) {
  const updateCaseNote = trpc.updateCaseNote.useMutation();

  const {
    register,
    handleSubmit,
    formState: { errors },
    setValue,
    watch,
    control,
  } = useForm<UpdateCaseNoteSchemaType>({
    resolver: zodResolver(UpdateCaseNoteSchema),
    defaultValues: {
      noteId: props.note.id,
      date: props.note.date,
      content: props.note.content,
    },
  });

  const onSubmit = async (input: UpdateCaseNoteSchemaType) => {
    const result = await updateCaseNote.mutateAsync(input);
  };

  return (
    <BaseForm handleSubmit={handleSubmit} onSubmit={onSubmit} preventParentFormTrigger control={control} ariaLabel="modifier la note du dossier">
      <Grid item xs={12} sm={6} md={4}>
        <DateTimePicker
          label="Date de la note"
          value={watch('date') || null}
          onChange={(newDate) => {
            setValue('date', newDate || new Date());
          }}
          renderInput={(params) => <TextField {...params} error={!!errors.date} helperText={errors?.date?.message} fullWidth />}
        />
      </Grid>
      <Grid item xs={12}>
        <EditorWrapper
          initialEditorState={control._defaultValues.content}
          onChange={(content: string) => {
            setValue('content', content, {
              shouldValidate: false,
            });
          }}
          error={errors?.content?.message}
        />
      </Grid>
      <Grid item xs={12}>
        <Button type="submit" loading={updateCaseNote.isLoading} size="large" variant="contained" fullWidth>
          Mettre à jour la note
        </Button>
      </Grid>
    </BaseForm>
  );
}
