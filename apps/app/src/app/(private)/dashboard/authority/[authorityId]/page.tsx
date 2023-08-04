'use client';

import { AuthorityPage, AuthorityPageProps } from '@aei/app/src/app/(private)/dashboard/authority/[authorityId]/AuthorityPage';

export interface CaseListPageProps {
  params: { authorityId: string };
}

export default function Page(props: AuthorityPageProps) {
  return <AuthorityPage {...props} />;
}
