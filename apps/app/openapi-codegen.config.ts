import { defineConfig } from '@openapi-codegen/cli';
import { generateReactQueryComponents, generateSchemaTypes } from '@openapi-codegen/typescript';

export default defineConfig({
  apiClient: {
    from: {
      relativePath: '../../apps/api/openapi.json',
      source: 'file',
    },
    outputDir: 'src/client/generated',
    to: async (context) => {
      const { schemasFiles } = await generateSchemaTypes(context, {});

      await generateReactQueryComponents(context, {
        schemasFiles,
      });
    },
  },
});
