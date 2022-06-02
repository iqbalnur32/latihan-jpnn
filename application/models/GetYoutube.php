<?php

class GetYoutube extends CI_Model
{
    public $table;
    
    public function __construct()
    {
        $this->table = "list_youtube";
    }

    public function query($query)
    {
        return $this->db->query($query);
    }

    public function absen_report()
    {
        return $this->db->get($this->table);
    }

    /* Query Youtube Datatable */
    public function ajaxDatatableYoutube($start_date, $end_date)
    {
        $column_search = array('id_list', 'title', 'youtubeID');
        $column_order = array('id_list', 'title', 'youtubeID');

        $this->db->from($this->table);
        if($start_date != '' && $end_date != ''){
            $this->db->where('publishedAt >=', $start_date);
            $this->db->where('publishedAt <=', $end_date);
        }
        // $this->db->where('list_youtube.publishedAt', 'BETWEEN "' . $start_date . '" AND "' . $end_date . '"');
        $i = 0;
        foreach ($column_search as $item) // loop kolom 
        {
            if ($this->input->post('search')['value']) // jika datatable mengirim POST untuk search
            {
                if ($i === 0) // looping pertama
                {
                    $this->db->group_start();
                    $this->db->like($item, $this->input->post('search')['value']);
                } else {
                    $this->db->or_like($item, $this->input->post('search')['value']);
                }
                if (count($column_search) - 1 == $i) //looping terakhir
                    $this->db->group_end();
            }
            $i++;
        }

        // jika datatable mengirim POST untuk order
        if ($this->input->post('order')) {
            $this->db->order_by($column_order[$this->input->post('order')['0']['column']], $this->input->post('order')['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function get_datatables_youtube($start_date, $end_date)
    {
        $this->ajaxDatatableYoutube($start_date, $end_date);
        if ($this->input->post('length') != -1)
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
            
        $query = $this->db->get();
        // echo $this->db->last_query();
        return $query->result();
    }

    public function count_filtered_youtube($start_date, $end_date)
    {
        $this->ajaxDatatableYoutube($start_date, $end_date);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all_youtube($start_date, $end_date)
    {
        $this->db->from($this->table);
        // $this->db->where('list_youtube.publishedAt', $publish_date);
        // $this->db->where('list_youtube.publishedAt', 'BETWEEN "' . $start_date . '" AND "' . $end_date . '"');
        if($start_date != '' && $end_date != ''){
            $this->db->where('publishedAt >=', $start_date);
            $this->db->where('publishedAt <=', $end_date);
        }
        return $this->db->count_all_results();
    }
}

?>