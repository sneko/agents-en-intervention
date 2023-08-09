import KeyboardArrowLeftRounded from '@mui/icons-material/KeyboardArrowLeftRounded';
import KeyboardArrowRightRounded from '@mui/icons-material/KeyboardArrowRightRounded';
import IconButton, { IconButtonProps } from '@mui/material/IconButton';

export default function ArrowButton({ direction, ...props }: { direction: 'left' | 'right' } & IconButtonProps) {
  const label = {
    left: 'Previous',
    right: 'Next',
  };
  return (
    <IconButton
      size="small"
      aria-label={label[direction]}
      {...props}
      sx={[
        {
          border: '1px solid',
          color: 'primary.main',
          '& + .MuiIconButton-root': {
            ml: 2,
          },
        },
        ...(Array.isArray(props.sx) ? props.sx : [props.sx]),
      ]}
    >
      {direction === 'left' && <KeyboardArrowLeftRounded fontSize="small" />}
      {direction === 'right' && <KeyboardArrowRightRounded fontSize="small" />}
    </IconButton>
  );
}
