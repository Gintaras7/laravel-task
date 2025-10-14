export const formatDate = (dateStr) => {
  if (!dateStr) return 'N/A';

  const date = new Date(dateStr);

  return date.toLocaleDateString(undefined, {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  });
};
