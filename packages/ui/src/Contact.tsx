import { useColors } from '@codegouvfr/react-dsfr/useColors';
import Button from '@mui/lab/LoadingButton';
import Container from '@mui/material/Container';
import Grid from '@mui/material/Grid';
import Typography from '@mui/material/Typography';

export function Contact() {
  const theme = useColors();

  return (
    <Container
      maxWidth={false}
      disableGutters
      sx={{
        bgcolor: theme.decisions.background.alt.blueFrance.default,
        py: { xs: 3, md: 4 },
      }}
    >
      <Container>
        <Grid container>
          <Grid item xs={12}>
            <Typography component="h2" variant="h4">
              Une question, un avis ? Contactez-nous
            </Typography>
            <Typography color="text.secondary" sx={{ mt: 1, mb: 2 }}>
              Si vous souhaitez contacter l'équipe Agents en intervention pour une question, un problème ou simplement donner votre avis dans le but
              d'améliorer le service, n'hésitez pas à nous écrire via le formulaire ci-dessous.
            </Typography>
            <Button href="https://tally.so/r/w4BgQd" size="large" variant="contained" target="_blank">
              Nous écrire
            </Button>
          </Grid>
        </Grid>
      </Container>
    </Container>
  );
}
