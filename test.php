<?php

// $url = 'https://tcc-test.auth.eu-central-1.amazoncognito.com/oauth2/token';
// $headers = [
//     'Content-Type: application/x-www-form-urlencoded',
//     'Authorization=Basic '.base64_encode("7o8erp8tjr36ui74j1dc6a8iif:1hdvt5o9u1frndf67e8tklrhjhj84or3smcbms4alkatld7i6it8"),
// ];
// $body = http_build_query([
//     'grant_type' => 'refresh_token',
//     'client_id' => '7o8erp8tjr36ui74j1dc6a8iif',
//     'refresh_token' => 
// ]);
// $ch = curl_init();
// curl_setopt($ch, CURLOPT_URL, $url);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($ch, CURLOPT_POST, true);
// curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
// curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// $response = curl_exec($ch);

// echo $response;
// if ($response === false) {
//     // Handle error
// }
// $data = json_decode($response, true);
// echo $accessToken = $data['access_token'];
// echo $newRefreshToken = $data['refresh_token'];


$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://tcc-test.auth.eu-central-1.amazoncognito.com/oauth2/token');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
    'grant_type' => 'refresh_token',
    'client_id' => '7o8erp8tjr36ui74j1dc6a8iif',
    'refresh_token' => 'eyJjdHkiOiJKV1QiLCJlbmMiOiJBMjU2R0NNIiwiYWxnIjoiUlNBLU9BRVAifQ.ukWiQPiWC0kQDrOUBIQPdKJdPb-YhYwlRQzG8RMKQ4bnkQwIcnTWJKC8j-tgbSL_lHaug-NCCLFmPTE8veQpO1JhwPgsFFNfHqC3SctW6weR2NfyHUNV8DXgdbnXfYBJIEUMPtTfsGUHAnhC26C3lx2ke-_6Po6KHQ8jX3CKGecwxSMeV0yH3hfqcnijWKxuvjDgR1R6fL0spv052CSF5-9EjrXBbS0alZ7cF8in0t78O6_tqgocDtKv8wVDusdjM33IasdF060zpKGBVk2VkYM265ECGxgD_S_p1kVKKLOQLL91LS15l_SweXw49eqFU3IpJFVcyDjuX67ZfC4BOQ.SYavPr6IBrpboI-k.gqZBMhyNSdf4ZtKntThkhdkM3cU6MF3gyHb0DmNygyr4GCMxxweH0_vsA4AEklGIva-1WfRUY-r_O5wmvIURh8YrGaim_SDY_97OCGY-4e9YqGqAs5jjtUUyFkxXYUykwunSh56HccUW_JfC4hTIWSl2j1ySwmQUyREudu-S9joKCaZ_ZUUdltaJjAZGBjqLtNbrO9Axpx00WoIhuvI1E1ZHU-3wdTw8cfrpSejhLPJTJfVXLTrKhBnOtIezae7GvnE3IchdS95S8UDSwHy3bjnq1BnU7mIkU2iqEIKL25rYVEwKMV3K7ooHern4DFTP-Qh6jedhzYOi4UN8kieY3wj9wt-iZDcXufnl3yw2Oz2k_4L7YlKhGSEzQdLJk_EqIdC6ZPAAxX2aaRA1Pvqmju8YwYzUMQqW-qfG4o3Zj_D7vthxJcLgrYoc1aNXfnI30Gs1WTNa8ZsJODpkd460WvXwmwIemmbX2MsMbtIav1O9Zsv8ogC4kjpRSSZ87HV1dhO3FpJo4Tu885ah23pOI3fUPY0yb9IhUUr6_t5vp74REEBxI8lGfYyi7TLw3STNSWV1mm3TvKiAJCdZ8I82oi_S2WjzS6F_wV0m1bHi2jGJE0e_MbqKq5KCXKvXyOuMVjlxBTuyFg1kP2dlkb8F9tWOG-LpeVAkBwE4Pbyrx-haqyjd01cUxA0ESxj23BQUU0u8zXMBWzF5dH3sS9yxPwsxic5LMKq0X7XDQVOdufhWDXgRyQ0ai5AFG02emWD7BigE7A6Lx2AT1ghbA8xEp38-FzqnXtfWjpy4alLCe2l5EjvYzP-snyNFgbQGFXjEheC1Ft5icLScKvgGxxCI3sM6bZ9feG-TrE90W4fotsygZJJxCapDSiDVmQd60FwjExF1f9X7JXqkoc6AUgqory397GyRO0jnPcYhVv0q0_Fcpek4DfvTEjLUVzCAzS6yD21ZMSF5NVurlsl7APuWjyq36QFn3W7KmueS8eqHhZ0OcoOGBlgJhFD4OJ_rl1z49Ln1RWvt4NzAXmFYLyqCuhU5xrGPZ_cNzwm9UVpt4toUaUCePyVy1ifDtKAPKQC91-7xKWiD7qMEPauEEKoX4O7o2bPFBTOm0uWscN54Nmlj_N-zGzp_GqZ1Xv5igORuEvUkYC32paThLmkXhnYZKbC1pGbrndk5Ys0_sJ8Wb4r5Hb-ZBuFMupx_0a54WxsVgvOszUgRdjOPGp-48UI6Q9ZN8NuDuiDh2DJCpMv8ayRg6MouEkCfb6I61oeBPlDkg8iO40RwJv921cmN8yxrkFrM004MxQ.6-aUUnjPQpv3DbeF-3HWwg'
)));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authorization: Basic ' . base64_encode("7o8erp8tjr36ui74j1dc6a8iif:1hdvt5o9u1frndf67e8tklrhjhj84or3smcbms4alkatld7i6it8"),
    'Content-Type: application/x-www-form-urlencoded',
));

$response = curl_exec($ch);
curl_close($ch);
echo $response;

