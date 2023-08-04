'use client';

import {
  CaseAssignationPage,
  CaseAssignationPageProps,
} from '@aei/app/src/app/(private)/dashboard/authority/[authorityId]/cases/unassigned/CaseAssignationPage';

export default function Page(props: CaseAssignationPageProps) {
  return <CaseAssignationPage {...props} />;
}
