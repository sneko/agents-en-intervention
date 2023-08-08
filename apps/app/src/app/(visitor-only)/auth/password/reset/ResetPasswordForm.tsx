'use client';

import { zodResolver } from '@hookform/resolvers/zod';
import VisibilityIcon from '@mui/icons-material/Visibility';
import VisibilityOffIcon from '@mui/icons-material/VisibilityOff';
import Button from '@mui/lab/LoadingButton';
import Grid from '@mui/material/Grid';
import IconButton from '@mui/material/IconButton';
import InputAdornment from '@mui/material/InputAdornment';
import TextField from '@mui/material/TextField';
import { useRouter } from 'next/navigation';
import { useState } from 'react';
import { useForm } from 'react-hook-form';

import { trpc } from '@aei/app/src/client/trpcClient';
import { BaseForm } from '@aei/app/src/components/BaseForm';
import { PasswordFieldHinter } from '@aei/app/src/components/PasswordFieldHinter';
import { ResetPasswordPrefillSchemaType, ResetPasswordSchema, ResetPasswordSchemaType } from '@aei/app/src/models/actions/auth';
import { linkRegistry } from '@aei/app/src/utils/routes/registry';

export function ResetPasswordForm({ prefill }: { prefill?: ResetPasswordPrefillSchemaType }) {
  const resetPassword = trpc.resetPassword.useMutation();
  const router = useRouter();

  const {
    register,
    handleSubmit,
    formState: { errors },
    watch,
    control,
  } = useForm<ResetPasswordSchemaType>({
    resolver: zodResolver(ResetPasswordSchema),
    defaultValues: prefill,
  });

  const onSubmit = async (input: ResetPasswordSchemaType) => {
    const result = await resetPassword.mutateAsync(input);

    router.push(linkRegistry.get('signIn', undefined));
  };

  const [showPassword, setShowPassword] = useState(false);
  const handleClickShowPassword = () => setShowPassword(!showPassword);
  const handleMouseDownPassword = () => setShowPassword(!showPassword);

  return (
    <BaseForm handleSubmit={handleSubmit} onSubmit={onSubmit} control={control} ariaLabel="redéfinir son mot de passe">
      <Grid item xs={12}>
        <TextField
          type={showPassword ? 'text' : 'password'}
          label="Nouveau mot de passe"
          {...register('password')}
          error={!!errors.password}
          helperText={errors?.password?.message}
          fullWidth
          InputProps={{
            endAdornment: (
              <InputAdornment position="end">
                <IconButton
                  aria-label="changer la visibilité du mot de passe"
                  onClick={handleClickShowPassword}
                  onMouseDown={handleMouseDownPassword}
                >
                  {showPassword ? <VisibilityIcon /> : <VisibilityOffIcon />}
                </IconButton>
              </InputAdornment>
            ),
          }}
        />
        <PasswordFieldHinter password={watch('password')} headline={`Le nouveau mot de passe doit contenir :`} />
      </Grid>
      <Grid item xs={12}>
        <Button type="submit" loading={resetPassword.isLoading} size="large" variant="contained" fullWidth>
          Mettre à jour
        </Button>
      </Grid>
    </BaseForm>
  );
}
