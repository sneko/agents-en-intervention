import 'i18next';

import { defaultNamespace, resources } from '@aei/app/src/i18n';

// We just use the default locale to define the typing structure
declare module 'i18next' {
  interface CustomTypeOptions {
    defaultNS: typeof defaultNamespace;
    resources: typeof resources['fr'];
  }
}
