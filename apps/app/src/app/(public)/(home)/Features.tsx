'use client';

import Grid from '@mui/material/Grid';

import calendar from '@aei/app/public/assets/home/calendar.png';
import intervention from '@aei/app/public/assets/home/intervention.png';
import { Feature } from '@aei/app/src/app/(public)/(home)/Feature';

export function Features() {
  return (
    <Grid
      container
      sx={{
        display: 'block',
        mx: 'auto',
      }}
    >
      <Feature
        {...{
          image: calendar,
          imageAlt: ``,
          name: `Une application web pour la coordination`,
          description: (
            <>
              <ul>
                <li>Programmer les interventions techniques en quelques clics</li>
                <li>Partager les informations de travail sur une application accessible à tous</li>
                <li>Suivre à distance les missions en cours</li>
                <li>Soulager l'équipe avec une organisation plus fluide</li>
              </ul>
            </>
          ),
        }}
      />
      <Feature
        {...{
          reverseItems: true,
          image: intervention,
          imageAlt: ``,
          name: `Une application mobile pour les agents`,
          description: (
            <>
              <ul>
                <li>Consulter sa liste des missions à effectuer, mise à jour en temps réel</li>
                <li>Accéder aux documents techniques à distance</li>
                <li>Communiquer sur l'avancée de son travail</li>
              </ul>
            </>
          ),
        }}
      />
    </Grid>
  );
}
