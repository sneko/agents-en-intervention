import { Meta, StoryFn } from '@storybook/react';

import { PrivateLayout } from '@aei/app/src/app/(private)/PrivateLayout';
import { Loading as PublicLayoutLoadingStory, Lorem as PublicLayoutLoremStory } from '@aei/app/src/app/(public)/PublicLayout.stories';
import { fetchApiUsersUserIdinterventionsGetCollection } from '@aei/app/src/client/generated/components';
import { getApiMock } from '@aei/app/src/client/mock';
import { userSessionContext } from '@aei/docs/.storybook/auth';
import { StoryHelperFactory } from '@aei/docs/.storybook/helpers';
import { playFindMain, playFindProgressBar } from '@aei/docs/.storybook/testing';

type ComponentType = typeof PrivateLayout;
const { generateMetaDefault, prepareStory } = StoryHelperFactory<ComponentType>();

export default {
  title: 'Layouts/PrivatePages',
  component: PrivateLayout,
  excludeStories: ['queryFactory'],
  ...generateMetaDefault({
    parameters: {
      layout: 'fullscreen',
      msw: {
        handlers: [],
      },
    },
  }),
} as Meta<ComponentType>;

export function queryFactory() {
  return {
    msw: {
      handlers: [
        getApiMock<typeof fetchApiUsersUserIdinterventionsGetCollection>({
          path: '/users/{userId}/interventions',
          response: [],
        }),
      ],
    },
  };
}

const Template: StoryFn<ComponentType> = (args) => {
  return <PrivateLayout {...args} />;
};

const AsNewUserStory = Template.bind({});
AsNewUserStory.args = {};
AsNewUserStory.parameters = {
  nextAuthMock: {
    session: userSessionContext,
  },
  ...queryFactory(),
};
AsNewUserStory.play = async ({ canvasElement }) => {
  await playFindMain(canvasElement);
};

export const AsNewUser = prepareStory(AsNewUserStory);

const LoremStory = Template.bind({});
LoremStory.args = {
  ...PublicLayoutLoremStory.args,
};
LoremStory.parameters = {
  ...AsNewUserStory.parameters,
};
LoremStory.play = async ({ canvasElement }) => {
  await playFindMain(canvasElement);
};

export const Lorem = prepareStory(LoremStory);
Lorem.storyName = 'With lorem';

const LoadingStory = Template.bind({});
LoadingStory.args = {
  ...PublicLayoutLoadingStory.args,
};
LoadingStory.parameters = {
  ...AsNewUserStory.parameters,
};
LoadingStory.play = async ({ canvasElement }) => {
  await playFindProgressBar(canvasElement, /chargement/i);
};

export const Loading = prepareStory(LoadingStory);
Loading.storyName = 'With loader';
