'use client';

import Grid from '@mui/material/Grid';

import { Features } from '@aei/app/src/app/(public)/(home)/Features';
import { Feedbacks } from '@aei/app/src/app/(public)/(home)/Feedbacks';
import { Introduction } from '@aei/app/src/app/(public)/(home)/Introduction';
import { Contact } from '@aei/ui/src/Contact';

export function HomePage() {
  return (
    <Grid
      container
      sx={{
        display: 'block',
        mx: 'auto',
      }}
    >
      <Introduction />
      <Features />
      <Feedbacks />
      <Contact />
    </Grid>
  );
}
