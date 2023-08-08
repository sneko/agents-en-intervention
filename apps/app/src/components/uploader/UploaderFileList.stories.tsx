import { Meta, StoryFn } from '@storybook/react';
import { within } from '@storybook/testing-library';
import { UppyFile } from '@uppy/core';

import { StoryHelperFactory } from '@aei/docs/.storybook/helpers';
import { UploaderFileList } from '@aei/app/src/components/uploader/UploaderFileList';
import {
  Complete as UploaderFileListItemCompleteStory,
  Failed as UploaderFileListItemFailedStory,
} from '@aei/app/src/components/uploader/UploaderFileListItem.stories';

type ComponentType = typeof UploaderFileList;
const { generateMetaDefault, prepareStory } = StoryHelperFactory<ComponentType>();

export default {
  title: 'Components/UploaderFileList',
  component: UploaderFileList,
  ...generateMetaDefault({
    parameters: {},
  }),
} as Meta<ComponentType>;

const Template: StoryFn<ComponentType> = (args) => {
  return <UploaderFileList {...args} />;
};

const SampleStory = Template.bind({});
SampleStory.args = {
  files: [
    { ...(UploaderFileListItemCompleteStory.args?.file as UppyFile), id: '1' },
    { ...(UploaderFileListItemFailedStory.args?.file as UppyFile), id: '2' },
    { ...(UploaderFileListItemCompleteStory.args?.file as UppyFile), id: '3' },
  ],
  onCancel: () => {},
  onRemove: () => {},
  onRetry: () => {},
};
SampleStory.parameters = {};
SampleStory.play = async ({ canvasElement }) => {
  await within(canvasElement).findAllByRole('button', {
    name: /supprimer/i,
  });
};

export const Sample = prepareStory(SampleStory);
