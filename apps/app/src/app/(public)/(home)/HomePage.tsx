'use client';

import Container from '@mui/material/Container';
import Grid from '@mui/material/Grid';
import Image from 'next/image';
import * as React from 'react';

import preview from '@aei/app/public/assets/home/preview.jpeg';
import { Features } from '@aei/app/src/app/(public)/(home)/Features';
import { Introduction } from '@aei/app/src/app/(public)/(home)/Introduction';
import { Partners } from '@aei/app/src/app/(public)/(home)/Partners';
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
      <Partners />
      <Container
        maxWidth={false}
        disableGutters
        sx={{
          display: 'flex',
        }}
      >
        <Image
          src={preview}
          alt=""
          style={{
            width: '100%',
            height: '100%',
            objectFit: 'contain',
            objectPosition: 'left center',
          }}
        />
      </Container>
      <Contact />
    </Grid>
  );
}
