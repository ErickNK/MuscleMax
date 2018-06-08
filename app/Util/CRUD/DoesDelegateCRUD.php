<?php

namespace App\Util\CRUD;


trait DoesDelegateCRUD {

    protected function removeKey($key,$item){
        $k = array_search($key,array_keys($item));
        if(!$k == false)
            array_splice($item,$k,1);
        return $item;
    }

    protected function hasTag($item){
        return array_key_exists('tag',$item);
    }

    protected function cherryPick($items,&$new,&$deleted,&$updated,$model){
        foreach ($items as $item) {
            if (!$this->hasTag($item)) {
                $this->errors['NoTag'] = $item['_id'];
                continue;
            }

            if ($item['tag'] == 'new') {
                $item['tag'] = $model;
                $new[] = $item;
            } elseif ($item['tag'] == 'deleted') {
                $item['tag'] = $model;
                $deleted[] = $item;
            } elseif ($item['tag'] == 'updated') {
                $item['tag'] = $model;
                $updated[] = $item;
            }
        }
    }

    /**
     * Perform all batch jobs synchronously.
     *
     * @param $deleted array
     */
    protected function doBatchJobs($new,$deleted,$updated){
        if (!empty($new)){
            $this->batchInsert($new);
        }
        if (!empty($deleted)){
            $this->batchDelete($deleted);
        }
        if (!empty($updated)){
            $this->batchUpdate($updated);
        }
    }

    /**
     * Perform a batch delete of items in array.
     *
     * @param $deleted array
     */
    public function batchDelete($deleted){
        foreach ($deleted as $item){
            if($f = call_user_func_array([$item['tag'],'find'],[$item['_id']])){
                if ($f->delete())
                    $this->info['Deleted'][] = $item['_id'];
                else
                    $this->errors['Delete']['Execution Error'][] = $item['_id'];
            }else{
                $this->errors['Delete']['Not Found'][] = $item['_id'];
            }
        }
    }

    /**
     * Perform a batch update of items in array.
     *
     * @param $updated array
     */
    public function batchUpdate($updated){
        foreach ($updated as $item){
            if($f = call_user_func_array([$item['tag'],'find'],[$item['_id']])){
                $this->removeKey('tag',$item);
                $this->removeKey('_id',$item);

                if ($f->update($item))
                    $this->info['Updated'][] = $item['_id'];
                else
                    $this->errors['Update']['Execution Error'][] = $item['_id'];
            }else{
                $this->errors['Update']['Not Found'][] = $item['_id'];
            }
        }
    }

    /**
     * Perform a batch insert of item in array.
     *
     * @param $new array
     */
    public function batchInsert($new){
        foreach ($new as $item){
            $this->removeKey('tag',$item);
            $this->removeKey('_id',$item);

            $f = call_user_func_array([$item['tag'],'create'],[$item]);
            if ($f)
                $this->info['Inserted'][]=['oldId'=>$item['_id'],'newId'=>$f->id];
            else
                $this->errors['Insert']['Execution Error'][] = $item['_id'];
        }
    }
}