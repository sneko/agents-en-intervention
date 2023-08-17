import { Meta, StoryFn } from '@storybook/react';

import { AsNewUser as PrivateLayoutAsNewUserStory } from '@aei/app/src/app/(private)/PrivateLayout.stories';
import { InterventionListPage } from '@aei/app/src/app/(private)/dashboard/interventions/InterventionListPage';
import { fetchApiEmployersEmployerIdinterventionsGetCollection } from '@aei/app/src/client/generated/components';
import { getApiMock } from '@aei/app/src/client/mock';
import { interventions } from '@aei/app/src/fixtures/intervention';
import { StoryHelperFactory } from '@aei/docs/.storybook/helpers';
import { playFindMainTitle } from '@aei/docs/.storybook/testing';

type ComponentType = typeof InterventionListPage;
const { generateMetaDefault, prepareStory } = StoryHelperFactory<ComponentType>();

export default {
  title: 'Pages/AdminList',
  component: InterventionListPage,
  ...generateMetaDefault({
    parameters: {},
  }),
} as Meta<ComponentType>;

const defaultMswParameters = {
  msw: {
    handlers: [
      getApiMock<typeof fetchApiEmployersEmployerIdinterventionsGetCollection>({
        path: '/users/{userId}/interventions',
        // response: [interventions[0], interventions[1], interventions[2]],
        response: [],
      }),
    ],
  },
};

async function playFindTitle(canvasElement: HTMLElement): Promise<HTMLElement> {
  return await playFindMainTitle(canvasElement, /interventions/i);
}

const Template: StoryFn<ComponentType> = (args) => {
  return <InterventionListPage {...args} />;
};

const NormalStory = Template.bind({});
NormalStory.args = {};
NormalStory.parameters = {
  ...defaultMswParameters,
};
NormalStory.play = async ({ canvasElement }) => {
  await playFindTitle(canvasElement);
};

export const Normal = prepareStory(NormalStory, {});

const WithLayoutStory = Template.bind({});
WithLayoutStory.args = {};
WithLayoutStory.parameters = {
  layout: 'fullscreen',
  ...defaultMswParameters,
};
WithLayoutStory.play = async ({ canvasElement }) => {
  await playFindTitle(canvasElement);
};

export const WithLayout = prepareStory(WithLayoutStory, {
  layoutStory: PrivateLayoutAsNewUserStory,
});
