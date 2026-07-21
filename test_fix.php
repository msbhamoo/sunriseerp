<?php
require "index.php"; 
$CI =& get_instance(); 
$student_session_id=1138; 
$sess = $CI->db->where("id", $student_session_id)->get("student_session")->row(); 
echo "Sess: " . json_encode($sess) . "\n"; 
$t_fee_yearly = $CI->db->where("student_session_id", $student_session_id)->get("student_transport_yearly_fees")->row(); 
echo "Tfee: " . json_encode($t_fee_yearly) . "\n"; 
if ($sess && empty($sess->route_pickup_point_id)) { 
    $existing_master = $CI->db->where("id", $t_fee_yearly->transport_yearly_feemaster_id)->get("transport_yearly_feemaster")->row(); 
    echo "Master: " . json_encode($existing_master) . "\n"; 
    if ($existing_master) { 
        $rpp_query = !empty($existing_master->route_pickup_point_id) 
            ? $CI->db->where('id', $existing_master->route_pickup_point_id)
            : $CI->db->where('pickup_point_id', $existing_master->pickup_point_id);
        $route_point = $rpp_query->get("route_pickup_point")->row(); 
        echo "RoutePoint: " . json_encode($route_point) . "\n"; 
        if ($route_point) { 
            $vr = $CI->db->where("route_id", $route_point->transport_route_id)->get("vehicle_routes")->row(); 
            echo "VR: " . json_encode($vr) . "\n"; 
            $CI->db->where("id", $student_session_id); 
            $res = $CI->db->update("student_session", ["route_pickup_point_id" => $route_point->id, "vehroute_id" => $vr ? $vr->id : null]); 
            echo "Update res: " . json_encode($res) . "\n"; 
            echo "DB Error: " . json_encode($CI->db->error()) . "\n"; 
        } 
    } 
}
