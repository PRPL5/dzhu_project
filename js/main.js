const  getData = async ()=> {
  const response = await fetch("../mockData/api.json");
  const data = await response.json();
  return data;
}

getData().then(data => {
  console.log(data);
});
