import { Meta, StoryFn } from '@storybook/react';
import { within } from '@storybook/testing-library';

import { StoryHelperFactory } from '@aei/docs/.storybook/helpers';
import { Messenger, MessengerContext } from '@aei/app/src/components/messenger/Messenger';
import { Normal as MessengerSidePanelNormalStory } from '@aei/app/src/components/messenger/MessengerSidePanel.stories';
import { ReceivedMessage as MessengerViewerReceivedMessageStory } from '@aei/app/src/components/messenger/MessengerViewer.stories';
import { messages } from '@aei/app/src/fixtures/messenger';
import { getTRPCMock } from '@aei/app/src/server/mock/trpc';

type ComponentType = typeof Messenger;
const { generateMetaDefault, prepareStory } = StoryHelperFactory<ComponentType>();

export default {
  title: 'Components/Messenger',
  component: Messenger,
  ...generateMetaDefault({
    parameters: {},
  }),
} as Meta<ComponentType>;

const defaultMswParameters = {
  msw: {
    handlers: [
      getTRPCMock({
        type: 'query',
        path: ['listMessages'],
        response: {
          messages: messages,
        },
      }),
    ],
  },
};

const Template: StoryFn<ComponentType> = (args) => {
  return <Messenger {...args} />;
};

const NormalStory = Template.bind({});
NormalStory.args = {};
NormalStory.parameters = {
  ...defaultMswParameters,
};
NormalStory.play = async ({ canvasElement }) => {
  await within(canvasElement).findByRole('button', {
    name: /nouveau message/i,
  });
};

export const Normal = prepareStory(NormalStory, {
  childrenContext: {
    context: MessengerContext,
    value: {
      ContextualMessengerSidePanel: MessengerSidePanelNormalStory,
      ContextualMessengerViewer: MessengerViewerReceivedMessageStory,
    },
  },
});
