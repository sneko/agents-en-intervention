'use client';

import { zodResolver } from '@hookform/resolvers/zod';
import Button from '@mui/lab/LoadingButton';
import Grid from '@mui/material/Grid';
import Link from '@mui/material/Link';
import TextField from '@mui/material/TextField';
import Typography from '@mui/material/Typography';
import NextLink from 'next/link';
import React from 'react';
import { useForm } from 'react-hook-form';

import { trpc } from '@aei/app/src/client/trpcClient';
import { BaseForm } from '@aei/app/src/components/BaseForm';
import { RequestNewPasswordPrefillSchemaType, RequestNewPasswordSchema, RequestNewPasswordSchemaType } from '@aei/app/src/models/actions/auth';
import { linkRegistry } from '@aei/app/src/utils/routes/registry';

export interface RetrievePasswordFormProps {
  prefill?: RequestNewPasswordPrefillSchemaType;
  onSuccess?: () => Promise<void>;
}

export function RetrievePasswordForm(props: RetrievePasswordFormProps) {
  const requestNewPassword = trpc.requestNewPassword.useMutation();

  const {
    register,
    handleSubmit,
    formState: { errors },
    control,
  } = useForm<RequestNewPasswordSchemaType>({
    resolver: zodResolver(RequestNewPasswordSchema),
    defaultValues: props.prefill,
  });

  const onSubmit = async (input: RequestNewPasswordSchemaType) => {
    const result = await requestNewPassword.mutateAsync(input);

    if (props.onSuccess) {
      await props.onSuccess();
    }
  };

  return (
    <BaseForm handleSubmit={handleSubmit} onSubmit={onSubmit} control={control} ariaLabel="demander à réinitialiser son mot de passe">
      <Grid item xs={12}>
        <TextField type="email" label="Email" {...register('email')} error={!!errors.email} helperText={errors?.email?.message} fullWidth />
      </Grid>
      <Grid item xs={12}>
        <Button type="submit" loading={requestNewPassword.isLoading} size="large" variant="contained" fullWidth>
          Valider
        </Button>
      </Grid>
      <Grid item xs={12}>
        <Typography color="textSecondary" variant="body2">
          <Link component={NextLink} href={linkRegistry.get('signIn', undefined)} variant="subtitle2" underline="none">
            Retourner à la page de connexion
          </Link>
        </Typography>
      </Grid>
    </BaseForm>
  );
}
