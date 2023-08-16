import { useColors } from '@codegouvfr/react-dsfr/useColors';
import { Box, ButtonBase } from '@mui/material';
import NextLink from 'next/link';

export interface SideNavItemProps {
  title: string;
  path: string;
  active: boolean;
  external?: boolean;
  icon: JSX.Element;
}

export function SideNavItem(props: SideNavItemProps) {
  const theme = useColors();

  const linkProps = props.path
    ? props.external
      ? {
          component: 'a',
          href: props.path,
          target: '_blank',
        }
      : {
          component: NextLink,
          href: props.path,
        }
    : {};

  return (
    <ButtonBase
      sx={{
        backgroundImage: 'none',
        alignItems: 'center',
        borderRadius: 1,
        display: 'flex',
        justifyContent: 'flex-start',
        pl: '16px',
        pr: '16px',
        py: '6px',
        textAlign: 'left',
        width: '100%',
        ...(props.active && {
          backgroundColor: theme.decisions.background.contrast.blueFrance.active,
        }),
        '&:hover': {
          backgroundColor: `${theme.decisions.background.contrast.blueFrance.hover} !important`,
        },
      }}
      {...linkProps}
    >
      {props.icon && (
        <Box
          component="span"
          sx={{
            alignItems: 'center',
            display: 'inline-flex',
            justifyContent: 'center',
            mr: 2,
            ...(props.active && {
              color: theme.decisions.text.title.blueFrance.default,
            }),
          }}
        >
          {props.icon}
        </Box>
      )}
      <Box
        component="span"
        sx={{
          flexGrow: 1,
          fontSize: 14,
          fontWeight: 600,
          lineHeight: '24px',
          whiteSpace: 'nowrap',
          ...(props.active && {
            color: theme.decisions.text.title.blueFrance.default,
          }),
        }}
      >
        {props.title}
      </Box>
    </ButtonBase>
  );
}
