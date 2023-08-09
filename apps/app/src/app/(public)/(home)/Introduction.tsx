import Button from '@mui/lab/LoadingButton';
import Box from '@mui/material/Box';
import Typography from '@mui/material/Typography';
import Image from 'next/image';
import * as React from 'react';

import hero from '@aei/app/public/assets/home/hero.png';
import { IntroductionContainer } from '@aei/ui/src/IntroductionContainer';

export function Introduction() {
  return (
    <IntroductionContainer
      left={
        <Box
          sx={{
            px: {
              sm: 4,
              lg: 1,
            },
            py: 3,
            textAlign: { xs: 'center', md: 'left' },
          }}
        >
          <Typography component="h1" variant="h1" sx={{ my: 2, maxWidth: 500 }}>
            Faciliter l'intervention des agents de votre collectivité
          </Typography>
          <Typography color="text.secondary" sx={{ mb: 3, maxWidth: 500 }}>
            L'application pour planifier et faciliter les interventions techniques dans votre collectivité.
          </Typography>
          <Button
            href="https://anct.pipedrive.com/scheduler/BpQAYMF0/presentation-et-demonstration-agents-en-intervention"
            size="large"
            variant="contained"
            target="_blank"
            sx={{ mb: 3 }}
          >
            Nous contacter
          </Button>
        </Box>
      }
      right={
        <Image
          src={hero}
          alt=""
          style={{
            width: '100%',
            maxHeight: '350px',
            objectFit: 'contain',
            objectPosition: 'left center',
          }}
        />
      }
    />
  );
}
