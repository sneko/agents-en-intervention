import { useContext } from 'react';

import { LiveChatContext } from '@aei/app/src/components/live-chat/LiveChatContext';

export const useLiveChat = () => {
  return useContext(LiveChatContext);
};
