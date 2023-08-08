import { Meta, StoryFn } from '@storybook/react';
import { within } from '@storybook/testing-library';

import { StoryHelperFactory } from '@aei/docs/.storybook/helpers';
import { reusableNormal as MessengerSenderNormalStory } from '@aei/app/src/components/messenger/MessengerSender.stories';
import { MessengerViewer, MessengerViewerContext } from '@aei/app/src/components/messenger/MessengerViewer';
import { messages } from '@aei/app/src/fixtures/messenger';
import { getTRPCMock } from '@aei/app/src/server/mock/trpc';

type ComponentType = typeof MessengerViewer;
const { generateMetaDefault, prepareStory } = StoryHelperFactory<ComponentType>();

export default {
  title: 'Components/MessengerViewer',
  component: MessengerViewer,
  ...generateMetaDefault({
    parameters: {},
  }),
} as Meta<ComponentType>;

const defaultMswParameters = {
  msw: {
    handlers: [
      getTRPCMock({
        type: 'mutation',
        path: ['updateMessageMetadata'],
        response: undefined,
      }),
    ],
  },
};

const Template: StoryFn<ComponentType> = (args) => {
  return <MessengerViewer {...args} />;
};

const ReceivedMessageStory = Template.bind({});
ReceivedMessageStory.args = {
  caseId: 'b79cb3ba-745e-5d9a-8903-4a02327a7e01',
  message: { ...messages[0], consideredAsProcessed: true },
};
ReceivedMessageStory.parameters = { ...defaultMswParameters };
ReceivedMessageStory.play = async ({ canvasElement }) => {
  await within(canvasElement).findByRole('button', {
    name: /répondre/i,
  });
};

export const ReceivedMessage = prepareStory(ReceivedMessageStory, {
  childrenContext: {
    context: MessengerViewerContext,
    value: {
      ContextualMessengerSender: MessengerSenderNormalStory,
    },
  },
});

const SentMessageStory = Template.bind({});
SentMessageStory.args = {
  caseId: 'b79cb3ba-745e-5d9a-8903-4a02327a7e01',
  message: { ...messages[0], consideredAsProcessed: null },
};
SentMessageStory.parameters = { ...defaultMswParameters };
SentMessageStory.play = async ({ canvasElement }) => {
  await within(canvasElement).findByRole('button', {
    name: /répondre/i,
  });
};

export const SentMessage = prepareStory(SentMessageStory, {
  childrenContext: {
    context: MessengerViewerContext,
    value: {
      ContextualMessengerSender: MessengerSenderNormalStory,
    },
  },
});
