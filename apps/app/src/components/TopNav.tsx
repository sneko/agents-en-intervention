import MenuIcon from '@mui/icons-material/Menu';
import Box from '@mui/material/Box';
import Button from '@mui/material/Button';
import IconButton from '@mui/material/IconButton';
import Stack from '@mui/material/Stack';
import Typography from '@mui/material/Typography';
import { alpha, useTheme } from '@mui/material/styles';
import useMediaQuery from '@mui/material/useMediaQuery';
import { EventEmitter } from 'eventemitter3';
import { useState } from 'react';

import { HeaderUserItem } from '@aei/app/src/components/HeaderUserItem';
import { TokenUserSchemaType } from '@aei/app/src/models/entities/user';

export const sideNavWidth = 280;
export const topNavHeight = 64;

export interface TopNavProps {
  onNavOpen: () => void;
}

export function TopNav(props: TopNavProps) {
  const theme = useTheme();
  const lgUp = useMediaQuery(theme.breakpoints.up('lg'));
  const [eventEmitter] = useState(() => new EventEmitter());

  // TODO: transform to prop
  const user: TokenUserSchemaType = {
    id: 'TODO',
    firstname: 'TODO',
    lastname: 'TODO',
    email: 'TODO',
    profilePicture: null,
  };

  return (
    <>
      <Box
        component="header"
        sx={{
          backdropFilter: 'blur(6px)',
          backgroundColor: (theme) => alpha(theme.palette.background.default, 0.8),
          position: 'sticky',
          left: {
            lg: `${sideNavWidth}px`,
          },
          top: 0,
          width: {
            lg: `calc(100% - ${sideNavWidth}px)`,
          },
          zIndex: (theme) => theme.zIndex.appBar,
        }}
      >
        <Stack
          alignItems="center"
          direction="row"
          justifyContent="space-between"
          spacing={2}
          sx={{
            minHeight: topNavHeight,
            px: 2,
          }}
        >
          <Stack alignItems="center" direction="row" spacing={2}>
            {!lgUp && (
              <IconButton onClick={props.onNavOpen} aria-label="changer la visibilité du menu">
                <MenuIcon />
              </IconButton>
            )}
            <Typography component="h1" variant="h4">
              Interventions TODO
            </Typography>
          </Stack>
          <Stack alignItems="center" direction="row" spacing={2}>
            <Button
              onClick={(event) => {
                eventEmitter.emit('click', event);
              }}
              variant="text"
            >
              <HeaderUserItem user={user} eventEmitter={eventEmitter} showDashboardMenuItem={false} />
            </Button>
          </Stack>
        </Stack>
      </Box>
    </>
  );
}
