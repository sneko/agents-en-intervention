'use client';

import { PropsWithChildren } from 'react';

import { PublicLayout } from '@aei/app/src/app/(public)/PublicLayout';

export default function Layout(props: PropsWithChildren) {
  return <PublicLayout>{props.children}</PublicLayout>;
}
