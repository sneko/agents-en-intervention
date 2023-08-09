import { useColors } from '@codegouvfr/react-dsfr/useColors';
import Box from '@mui/material/Box';
import Container from '@mui/material/Container';
import Grid from '@mui/material/Grid';
import Typography from '@mui/material/Typography';
import { useState } from 'react';
import SwipeableViews from 'react-swipeable-views';

import pierreYvesBrault from '@aei/app/public/assets/home/pierre-yves_brault.png';
import ArrowButton from '@aei/app/src/app/(public)/(home)/ArrowButton';
import { Feedback } from '@aei/app/src/app/(public)/(home)/Feedback';

const testimonials = [
  {
    quote:
      '"Grâce à Agents en Intervention (ex-SILAB), nos agents capturent, géolocalisent et décrivent leurs interventions en un seul geste sur leur smartphone. Plus de 6 900 actions enregistrées en 4 ans, avec des retours positifs des agents."',
    profile: {
      avatar: pierreYvesBrault,
      name: 'Pierre-Yves BRAULT',
      role: 'Direction des Solutions et Innovations Numériques - Grand Poitiers Communauté Urbaine',
    },
  },
  {
    quote:
      '"Grâce à Agents en Intervention (ex-SILAB), nos agents capturent, géolocalisent et décrivent leurs interventions en un seul geste sur leur smartphone. Plus de 6 900 actions enregistrées en 4 ans, avec des retours positifs des agents."',
    profile: {
      avatar: pierreYvesBrault,
      name: 'Pierre-Yves BRAULT',
      role: 'Direction des Solutions et Innovations Numériques - Grand Poitiers Communauté Urbaine',
    },
  },
];

export function Feedbacks() {
  const theme = useColors();

  const [slideIndex, setSlideIndex] = useState(0);

  return (
    <Container
      maxWidth={false}
      sx={{
        borderTop: `1px solid ${theme.decisions.background.alt.grey.active}`,
        py: { xs: 4, md: 5 },
      }}
    >
      <Container>
        <Grid container>
          <Grid item xs={12}>
            <Typography component="h2" variant="h4" sx={{ mb: 2 }}>
              Témoignages
            </Typography>
          </Grid>
          <Grid item xs={12}>
            <Box sx={{ maxWidth: { md: 700 } }}>
              <Box sx={{ display: 'flex', justifyContent: 'space-between', mb: 2 }}>
                <ArrowButton direction="left" disabled={slideIndex === 0} onClick={() => setSlideIndex((i) => i - 1)} />
                <ArrowButton
                  direction="right"
                  disabled={slideIndex === testimonials.length - 1}
                  onClick={() => setSlideIndex((i) => i + 1)}
                  sx={{ mr: 'auto' }}
                />
              </Box>
              <SwipeableViews index={slideIndex} onChangeIndex={(index) => setSlideIndex(index)}>
                {testimonials.map((item) => (
                  <Feedback key={item.profile.name} {...item} />
                ))}
              </SwipeableViews>
            </Box>
          </Grid>
        </Grid>
      </Container>
    </Container>
  );
}
