'use client';

import { PublicLayout } from '@aei/app/src/app/(public)/PublicLayout';
import { ErrorPage, error404Props } from '@aei/ui/src/ErrorPage';

export function Error404({ error, reset }: { error: Error; reset: () => void }) {
  return (
    <>
      <PublicLayout>
        <ErrorPage {...error404Props} />
      </PublicLayout>
    </>
  );
}

export default Error404;
