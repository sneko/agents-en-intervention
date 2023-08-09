import Box, { BoxProps } from '@mui/material/Box';
import Container from '@mui/material/Container';
import Grid from '@mui/material/Grid';
import * as React from 'react';

export function IntroductionContainer({
  left,
  right,
  rightRef,
  rightSx,
  containerMaxHeight,
}: {
  left: React.ReactElement;
  right: React.ReactElement;
  rightRef?: React.MutableRefObject<HTMLDivElement | null>;
  rightSx?: BoxProps['sx'];
  containerMaxHeight?: any; // Tried to type `BoxProps['sx']['maxHeight']` but it's not working
}) {
  const containerMaxHeightToUse = containerMaxHeight || { xs: 500, sm: 550, xl: 600 };

  return (
    <Box
      sx={{
        overflow: 'hidden',
        // TODO: forced to use this color since I don't have the original image PDF with transparent bg
        // bgcolor: theme.decisions.background.alt.grey.default,
        bgcolor: '#edebfe',
      }}
    >
      <Container
        sx={{
          minHeight: Math.min(...Object.values<number>(containerMaxHeightToUse)),
          height: 'calc(100vh - 120px)',
          maxHeight: containerMaxHeightToUse,
          transition: '0.3s',
        }}
      >
        <Grid
          container
          alignItems="center"
          wrap="nowrap"
          sx={{
            height: '100%',
            mx: 'auto',
          }}
        >
          <Grid item md={7} lg={6} sx={{ m: 'auto' }}>
            {left}
          </Grid>
          <Grid item md={5} lg={6} sx={{ maxHeight: '100%', display: { xs: 'none', md: 'initial' } }}>
            <Box
              ref={rightRef}
              id="introduction-container-right-area"
              aria-hidden="true"
              sx={[
                {
                  display: 'flex',
                  alignItems: 'center',
                  px: '3vw',
                  py: '20px',
                  minWidth: {
                    md: `${100 / (12 / 5)}vw`,
                    lg: `${100 / (12 / 6)}vw`,
                  },
                  minHeight: Math.min(...Object.values<number>(containerMaxHeightToUse)),
                  height: 'calc(100vh - 120px)',
                  maxHeight: containerMaxHeightToUse,
                  transition: 'max-height 0.3s',
                },
                ...(Array.isArray(rightSx) ? rightSx : [rightSx]),
              ]}
            >
              {right}
            </Box>
          </Grid>
        </Grid>
      </Container>
    </Box>
  );
}
