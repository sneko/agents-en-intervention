import Avatar from '@mui/material/Avatar';
import Box from '@mui/material/Box';
import Typography from '@mui/material/Typography';
import { StaticImageData } from 'next/image';

export function Feedback({
  quote,
  profile,
}: {
  quote: string;
  profile: {
    avatar: StaticImageData;
    name: string;
    role: string;
    company?: React.ReactElement;
  };
}) {
  return (
    <Box>
      <Typography variant="body1" component="div" sx={{ mb: 2.5 }}>
        {quote}
      </Typography>
      <Box sx={{ display: 'flex', alignItems: 'center', mt: 3 }}>
        <Avatar
          src={profile.avatar.src}
          alt={`Picture of ${profile.name}`}
          imgProps={{ loading: 'lazy' }}
          sx={{
            width: 58,
            height: 58,
            border: '2px solid',
            borderColor: 'primary.200',
            bgcolor: 'grey.800',
          }}
        />
        <Box sx={{ ml: 2 }}>
          <Typography variant="body2" fontWeight="500">
            {profile.name},{' '}
            <Box component="span" sx={{ fontWeight: 'regular' }}>
              {profile.role}
            </Box>
          </Typography>
        </Box>
      </Box>
    </Box>
  );
}
