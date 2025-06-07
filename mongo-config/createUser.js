db = db.getSiblingDB('meubanco'); // Seleciona o banco 'meubanco'

db.createUser({
  user: "meuusuario",
  pwd: "senhasecreta",
  roles: [{ role: "readWrite", db: "meubanco" }]
});
