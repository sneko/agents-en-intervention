'use client';

import { Footer } from '@codegouvfr/react-dsfr/Footer';
import { Header, HeaderProps } from '@codegouvfr/react-dsfr/Header';
import { usePathname } from 'next/navigation';
import { PropsWithChildren } from 'react';

import { useSession } from '@aei/app/src/proxies/next-auth/react';
import { commonFooterAttributes, commonHeaderAttributes, userQuickAccessItem } from '@aei/app/src/utils/dsfr';
import { linkRegistry } from '@aei/app/src/utils/routes/registry';
import { hasPathnameThisMatch } from '@aei/app/src/utils/url';
import { LoadingArea } from '@aei/ui/src/LoadingArea';
import { ContentWrapper } from '@aei/ui/src/layouts/ContentWrapper';

export function PublicLayout(props: PropsWithChildren) {
  const sessionWrapper = useSession();
  const pathname = usePathname();

  if (sessionWrapper.status === 'loading') {
    return <LoadingArea ariaLabelTarget="contenu" />;
  }

  // TODO: display a loading... maybe on the whole layout?
  let quickAccessItems: HeaderProps.QuickAccessItem[] | undefined;
  if (sessionWrapper.status === 'authenticated') {
    quickAccessItems = [
      userQuickAccessItem(sessionWrapper.data?.user, {
        showDashboardMenuItem: true,
      }),
    ];
  } else {
    quickAccessItems = [
      {
        iconId: 'fr-icon-lock-line',
        linkProps: {
          href: linkRegistry.get('signIn', undefined),
        },
        text: 'Se connecter',
      },
    ];
  }

  const homeLink = linkRegistry.get('home', undefined);
  const featuresLink = linkRegistry.get('features', undefined);
  const aboutUsLink = linkRegistry.get('aboutUs', undefined);

  return (
    <>
      <Header {...commonHeaderAttributes} quickAccessItems={quickAccessItems} />
      <ContentWrapper>{props.children}</ContentWrapper>
      <Footer {...commonFooterAttributes} />
    </>
  );
}
