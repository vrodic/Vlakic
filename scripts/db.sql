CREATE TABLE Stations (
  id    SERIAL, 
  name  varchar(255) NOT NULL 
);
  
CREATE TABLE StationMovables (
  statid int NOT NULL,
  movableid varchar(10) NOT NULL,
  coming time,
  going time,
  fromstation int,
  tostation int,
  extratxt      text,
  sun int,
  sat int,
  extratxthandled int
);

CREATE TABLE ToFromSelections( 
  _id    SERIAL,
  userid int,
  fromstation int,
  tostation int,
  name text,
  count int
);






