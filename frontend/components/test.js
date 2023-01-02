

export default function MyComponent() {
  function createMarkup() {
    return {__html: '<ol><li>hello bro</li></ol>'};
  }
  return <div dangerouslySetInnerHTML={createMarkup()} />;
}
