window.onload = () => {
  ctx = document.querySelector("canvas").getContext("2d");

  ctx.fillStyle = window.map_context.map_var;
  ctx.fillRect(0, 0, 1024, 768);
  ctx.fillStyle = window.map_context.text2_var;

  for (let road of window.map_context.roads) {
    let start = window.map_context.points[road.start_point - 1];
    let end = window.map_context.points[road.end_point - 1];

    let dir = {
      x:
        (end.x - start.x) /
        Math.sqrt((end.x - start.x) ** 2 + (end.y - start.y) ** 2),
      y:
        (end.y - start.y) /
        Math.sqrt((end.x - start.x) ** 2 + (end.y - start.y) ** 2),
    };

    let bias = {
      x: 3 * dir.y,
      y: -3 * dir.x,
    };

    ctx.lineWidth = 4;
    ctx.strokeStyle = window.map_context.road_var;

    ctx.beginPath();
    ctx.moveTo(
      window.map_context.center.x + start.x + bias.x,
      window.map_context.center.y + start.y + bias.y
    );
    ctx.lineTo(
      window.map_context.center.x + end.x + bias.x,
      window.map_context.center.y + end.y + bias.y
    );
    ctx.stroke();

    ctx.beginPath();
    ctx.moveTo(
      window.map_context.center.x + start.x - bias.x,
      window.map_context.center.y + start.y - bias.y
    );
    ctx.lineTo(
      window.map_context.center.x + end.x - bias.x,
      window.map_context.center.y + end.y - bias.y
    );
    ctx.stroke();

    const ccar = window.map_context.cars.filter(
      (x) => x.road_id == road.id
    ).length;

    const clength = Math.sqrt((end.x - start.x) ** 2 + (end.y - start.y) ** 2);

    const clight =
      window.map_context.traffic_lights.filter(
        (x) => x.position == end.id && x.color == "R"
      ).length -
      window.map_context.traffic_lights.filter(
        (x) => x.position == end.id && x.color == "G"
      ).length;

    let add = 0;
    const cratio = (ccar * 100) / clength;
    if (
      cratio <= window.map_context.params[4].value &&
      window.map_context.params[4].value > 0
    ) {
      add = (cratio + window.map_context.params[4].value) / 2;
    } else {
      add =
        window.map_context.params[4].value +
        1 -
        Math.exp(cratio - window.map_context.params[4].value);
    }

    const k =
      window.map_context.params[3].value -
      (window.map_context.params[0].value * ccar +
        window.map_context.params[1].value * clength +
        window.map_context.params[2].value * clight) +
      add;

    ctx.lineWidth = 1;
    ctx.fillStyle = window.map_context.text1_var;

    const text = k.toFixed(2);
    const text_bounds = {
      w: text.length * 10,
      h: 20,
    };

    ctx.font = "10px serif";
    ctx.fillText(
      "#" + road.id,
      window.map_context.center.x + (start.x + dir.x * 50) - 7.5 - 5 * bias.x,
      window.map_context.center.y + (start.y + dir.y * 50) + 5 - 5 * bias.y
    );

    ctx.font = "20px serif";
    ctx.fillText(
      text,
      window.map_context.center.x +
        (start.x + end.x) / 2 -
        text_bounds.w / 2 -
        10 * bias.x,
      window.map_context.center.y +
        (start.y + end.y) / 2 +
        text_bounds.h / 2 -
        10 * bias.y
    );
  }

  ctx.fillStyle = "white";
  for (let point of window.map_context.points) {
    ctx.fillStyle = window.map_context.point_var;
    ctx.beginPath();
    ctx.arc(
      window.map_context.center.x + point.x,
      window.map_context.center.y + point.y,
      10,
      0,
      2 * Math.PI
    );
    ctx.fill();

    ctx.font = "10px serif";
    ctx.fillStyle = window.map_context.text2_var;
    ctx.fillText(
      point.id,
      window.map_context.center.x + point.x - 2.5,
      window.map_context.center.y + point.y + 2.5
    );
  }

  ctx.fillStyle = "royalblue";

  for (let car of window.map_context.cars) {
    if (window.map_context.current_car)
      if (window.map_context.current_car.id == car.id) {
        ctx.fillStyle = "gold";
      }

    if (car.road_id && car.distance) {
      let road = window.map_context.roads[car.road_id - 1]; //roads должно быть отсортировано по возрастанию id!!!

      let start = window.map_context.points[road.start_point - 1];
      let end = window.map_context.points[road.end_point - 1];

      let dir = {
        x:
          (end.x - start.x) /
          Math.sqrt((end.x - start.x) ** 2 + (end.y - start.y) ** 2),
        y:
          (end.y - start.y) /
          Math.sqrt((end.x - start.x) ** 2 + (end.y - start.y) ** 2),
      };

      let bias = {
        x: 3 * dir.y,
        y: -3 * dir.x,
      };

      let car_pos = {
        x: start.x + (end.x - start.x) * car.distance - bias.x,
        y: start.y + (end.y - start.y) * car.distance - bias.y,
      };
      ctx.fillRect(
        window.map_context.center.x + car_pos.x - 5,
        window.map_context.center.y + car_pos.y - 5,
        10,
        10
      );
    }

    ctx.fillStyle = "royalblue";
  }

  for (let traffic_light of window.map_context.traffic_lights) {
    ctx.fillStyle = traffic_light.color === "R" ? "red" : "green";

    let road = window.map_context.roads[traffic_light.direction - 1];
    let start = window.map_context.points[road.start_point - 1];
    let end = window.map_context.points[road.end_point - 1];

    let dir = {
      x:
        (end.x - start.x) /
        Math.sqrt((end.x - start.x) ** 2 + (end.y - start.y) ** 2),
      y:
        (end.y - start.y) /
        Math.sqrt((end.x - start.x) ** 2 + (end.y - start.y) ** 2),
    };

    ctx.fillRect(
      window.map_context.center.x + start.x + 15 * dir.x - 5,
      window.map_context.center.y + start.y + 15 * dir.y - 5,
      10,
      10
    );

    if (window.map_context.current_lights_ids.includes(traffic_light.id)) {
      ctx.fillStyle = "white";

      let dx = traffic_light.id > 9 ? 5 : 3;

      ctx.fillText(
        traffic_light.id,
        center.x + start.x + 15 * dir.x - dx,
        center.y + start.y + 15 * dir.y + 3
      );
    }
  }
};
