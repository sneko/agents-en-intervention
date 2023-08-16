import { useColors } from '@codegouvfr/react-dsfr/useColors';
import AccountCircleIcon from '@mui/icons-material/AccountCircle';
import ArticleIcon from '@mui/icons-material/Article';
import HomeIcon from '@mui/icons-material/Home';
import UnfoldMoreIcon from '@mui/icons-material/UnfoldMore';
import Box from '@mui/material/Box';
import Divider from '@mui/material/Divider';
import Drawer from '@mui/material/Drawer';
import Stack from '@mui/material/Stack';
import Typography from '@mui/material/Typography';
import { useTheme } from '@mui/material/styles';
import useMediaQuery from '@mui/material/useMediaQuery';
import Image from 'next/image';
import NextLink from 'next/link';
import { usePathname } from 'next/navigation';

import marianne from '@aei/app/public/assets/images/marianne.svg';
import { SideNavItem } from '@aei/app/src/components/SideNavItem';
import { ulComponentResetStyles } from '@aei/app/src/utils/grid';

export const items = [
  {
    title: 'Accueil',
    path: '/',
    icon: <HomeIcon fontSize="small" />,
  },
  {
    title: 'Interventions',
    path: '/todo',
    icon: <ArticleIcon fontSize="small" />,
  },
  {
    title: 'Équipe',
    path: '/todo-2',
    icon: <AccountCircleIcon fontSize="small" />,
  },
];

export interface SideNavProps {
  open: boolean;
  onClose: () => void;
}

export function SideNav(props: SideNavProps) {
  const muiTheme = useTheme();
  const theme = useColors();

  const pathname = usePathname();
  const lgUp = useMediaQuery(muiTheme.breakpoints.up('lg'));

  const content = (
    <Box
      sx={{
        display: 'flex',
        flexDirection: 'column',
        height: '100%',
      }}
    >
      <Box sx={{ p: 3 }}>
        <Box
          component={NextLink}
          href="/"
          sx={{
            backgroundImage: 'none',
          }}
        >
          <Image src={marianne} alt="" height={20} style={{ objectFit: 'contain' }} />
          <Typography variant="h5">Agents en intervention</Typography>
        </Box>
        <Box
          sx={{
            bgcolor: theme.decisions.background.contrast.blueFrance.default,
            alignItems: 'center',
            borderRadius: 1,
            cursor: 'pointer',
            display: 'flex',
            justifyContent: 'space-between',
            mt: 2,
            p: '12px',
          }}
        >
          <div>
            <Typography color="inherit" variant="subtitle1">
              Collectivité
            </Typography>
            <Typography variant="body2" sx={{ fontWeight: 700 }}>
              Brest
            </Typography>
          </div>
          <UnfoldMoreIcon fontSize="small" />
        </Box>
      </Box>
      <Divider variant="fullWidth" sx={{ p: 0 }} />
      <Box
        component="nav"
        sx={{
          flexGrow: 1,
          px: 2,
          py: 3,
        }}
      >
        <Stack component="ul" spacing={0.5} sx={ulComponentResetStyles}>
          {items.map((item) => {
            const active = item.path ? pathname === item.path : false;

            return (
              <li key={item.path}>
                <SideNavItem active={active} icon={item.icon} key={item.title} path={item.path} title={item.title} />
              </li>
            );
          })}
        </Stack>
      </Box>
      <Divider variant="fullWidth" sx={{ p: 0 }} />
      <Box
        sx={{
          px: 2,
          py: 3,
        }}
      >
        <Typography variant="subtitle2">Il manque une fonctionnalité ?</Typography>
        <Typography variant="body2">N'hésitez pas à nous contacter !</Typography>
      </Box>
    </Box>
  );

  if (lgUp) {
    return (
      <Drawer
        anchor="left"
        open
        PaperProps={{
          sx: {
            width: 280,
          },
        }}
        variant="permanent"
      >
        {content}
      </Drawer>
    );
  }

  return (
    <Drawer
      anchor="left"
      onClose={props.onClose}
      open={props.open}
      PaperProps={{
        sx: {
          width: 280,
        },
      }}
      sx={{ zIndex: (theme) => theme.zIndex.appBar + 100 }}
      variant="temporary"
    >
      {content}
    </Drawer>
  );
}
