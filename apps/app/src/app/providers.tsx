'use client';

import { AdapterDateFns } from '@mui/x-date-pickers/AdapterDateFns';
import { LocalizationProvider } from '@mui/x-date-pickers/LocalizationProvider';
import { PropsWithChildren } from 'react';
import { createContext, useContext } from 'react';
import { I18nextProvider } from 'react-i18next';

import { dateFnsLocales, i18n } from '@aei/app/src/i18n';
import { SessionProvider } from '@aei/app/src/proxies/next-auth/react';
import { ModalProvider } from '@aei/ui/src/modal/ModalProvider';

export const ProvidersContext = createContext({
  ContextualSessionProvider: SessionProvider,
});

// [IMPORTANT] Some providers rely on hooks so we extracted them from here so this can be reused in Storybook without a burden
// Consider `Providers` as something common to both Storybook and the runtime application

export function Providers(props: PropsWithChildren) {
  const { ContextualSessionProvider } = useContext(ProvidersContext);

  return (
    <LocalizationProvider dateAdapter={AdapterDateFns} adapterLocale={dateFnsLocales[i18n.language]}>
      <I18nextProvider i18n={i18n}>
        <ModalProvider>
          <ContextualSessionProvider>{props.children}</ContextualSessionProvider>
        </ModalProvider>
      </I18nextProvider>
    </LocalizationProvider>
  );
}
