'use client';

import {
  AuthorityMetricsPage,
  AuthorityMetricsPageProps,
} from '@aei/app/src/app/(private)/dashboard/authority/[authorityId]/metrics/AuthorityMetricsPage';

export default function Page(props: AuthorityMetricsPageProps) {
  return <AuthorityMetricsPage {...props} />;
}
