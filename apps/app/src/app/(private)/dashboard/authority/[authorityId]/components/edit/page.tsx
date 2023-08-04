'use client';

import {
  AuthorityComponentsEditPage,
  AuthorityComponentsEditPageProps,
} from '@aei/app/src/app/(private)/dashboard/authority/[authorityId]/components/edit/AuthorityComponentsEditPage';

export default function Page(props: AuthorityComponentsEditPageProps) {
  return <AuthorityComponentsEditPage {...props} />;
}
