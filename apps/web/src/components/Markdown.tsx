import { useMemo } from 'react';
import { marked } from 'marked';
import DOMPurify from 'dompurify';

export default function Markdown({ content }: { content?: string | null }) {
  const html = useMemo(() => {
    if (!content) return '';
    const raw = marked.parse(content, { async: false }) as string;
    return DOMPurify.sanitize(raw);
  }, [content]);

  if (!content) return null;
  return <div className="prose prose-invert prose-sm max-w-none" dangerouslySetInnerHTML={{ __html: html }} />;
}
