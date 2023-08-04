import { signOut } from '@aei/app/src/proxies/next-auth/react';

export const logout = async () => {
  await signOut({
    redirect: true,
    callbackUrl: '/auth/sign-in?session_end',
  });
};
